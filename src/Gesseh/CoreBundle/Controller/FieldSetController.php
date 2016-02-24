<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $arg['limit'] = $this->get('request')->query->get('limit', null);
    if ($arg['limit']['type'] == 'u.username' and $arg['limit']['value'] == '') {
        $username = $this->get('security.token_storage')->getToken()->getUsername();
        $arg['limit']['value'] = $username;
        $arg['limit']['description'] = $username;
    }

    $pm = $this->container->get('kdb_parameters.manager');
    if($pm->findParamByName('simul_active')->getValue()) {
        $period = $em->getRepository('GessehCoreBundle:Period')->getLast();
        if($period) {
            $arg['period'] = $period->getId();
        } else {
            $arg['period'] = null;
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
    $clusters = null;

    foreach($department->getRepartitions() as $repartition) {
        if ($cluster_name = $repartition->getCluster()) {
            $period = $repartition->getPeriod();
            $clusters[] = array(
                'period'       => $period,
                'repartitions' => $em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($period->getId(), $cluster_name),
            );
        }
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
        'clusters'    => $clusters,
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

    /**
     * Database update if needed
     *
     * @Route("/update", name="GCore_DBUpdate")
     */
    public function updateAction()
    {
        /** Update database if some migrations are pending */
        if ($this->hasToMigrate($this->getDoctrine()->getConnection())) {
            $this->get('session')->getFlashBag()->add('notice', 'Mise à jour de la base de donnée effectuée.');

            $em = $this->getDoctrine()->getManager();

            /** Go to first user form if repository User is empty */
            if(!$em->getRepository('GessehUserBundle:User')->findAll()){
                return $this->redirect($this->generateUrl('GUser_SInstall'));
            }
        } else {
            $this->get('session')->getFlashBag()->add('notice', 'Toutes les mises à jour de la base de donnée ont déjà été effectuées.');
        }

        return $this->redirect($this->generateUrl('GCore_FSIndex'));
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

}
