<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Entity\Hospital;
use Doctrine\DBAL\Migrations\Migration,
  Doctrine\DBAL\Migrations\Configuration\Configuration;

/**
 * FieldSet controller.
 */
class FieldSetController extends Controller
{
  /**
   * @Route("/fieldset/", name="GCore_FSIndex")
   * @Route("/", name="homepage")
   * @Route("/update", name="GCore_DBUpdate")
   * @Template()
   */
  public function indexAction()
  {
      /** Update database if some migrations are pending */
      if ($this->hasToMigrate($this->getDoctrine()->getConnection())) {
            $this->get('session')->getFlashBag()->add('notice', 'Mise à jour de la base de donnée effectuée.');
            return $this->redirect($this->generateUrl('GCore_FSIndex'));
      }

      $em = $this->getDoctrine()->getManager();

      /** Go to first user form if repository User is empty */
      if(!$em->getRepository('GessehUserBundle:User')->findAll()){
          return $this->redirect($this->generateUrl('GUser_SInstall'));
      }

    $arg['limit'] = $this->get('request')->query->get('limit', null);

    $pm = $this->container->get('kdb_parameters.manager');
    if($pm->findParamByName('simul_active')->getValue()) {
        $period = $em->getRepository('GessehCoreBundle:Period')->getLast();
        if($period) {
            $arg['period'] = $period->getId();
        }
    } else {
        $arg['period'] = null;
    }

    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->getAll($arg);

    return array(
        'hospitals' => $hospitals,
        'limit'     => $arg['limit'],
    );
  }

    private function hasToMigrate($conn)
    {
      $dir = __DIR__.'/../../../../app/DoctrineMigrations';
      $configuration = new Configuration($conn);
      $configuration->setMigrationsNamespace('Application\Migrations');
      $configuration->setMigrationsTableName('migration_versions');
      $configuration->setMigrationsDirectory($dir);
      $configuration->registerMigrationsFromDirectory($dir);

      $executedMigrations = $configuration->getMigratedVersions();
      $availableMigrations = $configuration->getAvailableVersions();
      $newMigrations = count($availableMigrations) - count($executedMigrations);
      $executedUnavailableMigrations = array_diff($executedMigrations, $availableMigrations);

      if ($newMigrations > 0 and !$executedUnavailableMigrations) {
        $migration = new Migration($configuration);
        if ($migration->migrate()) {
            return true;
        }
      }
      return false;
    }

  /**
   * @Route("/department/{id}/show", name="GCore_FSShowDepartment", requirements={"id" = "\d+"})
   * @Template()
   */
  public function showDepartmentAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $pm = $this->container->get('kdb_parameters.manager');
    $user = $this->get('security.token_storage')->getToken()->getUsername();
    $department = $em->getRepository('GessehCoreBundle:Department')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if ($cluster_name = $department->getCluster()) {
        $cluster = $em->getRepository('GessehCoreBundle:Department')->findByCluster($cluster_name);
    } else {
        $cluster = null;
    }

    if (true == $pm->findParamByName('eval_active')->getValue()) {
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array', $user);
    } else {
        $evaluated = array();
    }

    return array(
        'department' => $department,
        'evaluated'  => $evaluated,
        'limit'      => $limit,
        'cluster'    => $cluster,
    );
  }

  /**
   * Finds and displays a Hospital entity.
   *
   * @Route("/hospital/{id}/show", name="GCore_FSShowHospital")
   * @Template()
   */
  public function showHospitalAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $pm = $this->container->get('kdb_parameters.manager');
    $user = $this->get('security.token_storage')->getToken()->getUsername();
    $hospital = $em->getRepository('GessehCoreBundle:Hospital')->find($id);
    $limit = $this->get('request')->query->get('limit', null);

    if (!$hospital) {
        throw $this->createNotFoundException('Unable to find Hospital entity.');
    }

    return array(
        'hospital' => $hospital,
        'limit'    => $limit,
    );
  }
}
