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
    $pm = $this->container->get('kdb_parameters.manager');
    $um = $this->container->get('fos_user.user_manager');
    $user = $um->findUserByUsername($this->get('security.token_storage')->getToken()->getUsername());

    $sectors = $em->getRepository('GessehCoreBundle:Sector')->findAll();
    if ($sector_default = $em->getRepository('GessehCoreBundle:Sector')->findOneBy(array('is_default' => true,)))
    {
        $limit_default = array(
            'type'        => 's.id',
            'value'       => $sector_default->getId(),
            'description' => $sector_default,
        );
    } else {
        $limit_default = null;
    }

    /* Filtre sur le username pour l'entrée du menu Teacher */
    $arg['limit'] = $this->get('request')->query->get('limit', $limit_default);
    if ($arg['limit']['type'] == 'u.id' and $arg['limit']['value'] == '') {
        $arg['limit']['value'] = $user->getId();
        $arg['limit']['description'] = $user->getUsername();
    }

    $period = $em->getRepository('GessehCoreBundle:Period')->getLast();
    if($period) {
        $arg['period'] = $period->getId();
    } else {
        $arg['period'] = null;
    }
    $arg['period'] = null;

    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->getAll($arg);

    return array(
        'hospitals' => $hospitals,
        'sectors'   => $sectors,
        'limit'     => $arg['limit'],
    );
  }

  /**
   * @Route("/{id}/show", name="GCore_FSShowDepartment", requirements={"id" = "\d+"})
   * @Template()
   */
  public function showAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $pm = $this->container->get('kdb_parameters.manager');
    $username = $this->get('security.token_storage')->getToken()->getUsername();
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

    $evaluated = array();
    $placements = $em->getRepository('GessehCoreBundle:Placement')->getByUsernameAndDepartment($username, $department->getId());
    if (true == $pm->findParamByName('eval_active')->getValue() and null !== $placements) {
        foreach ($placements as $placement) {
            $evaluated[$placement->getId()] = $em->getRepository('GessehEvaluationBundle:Evaluation')->getByPlacement($placement->getId());
        }
    }

    return array(
        'department' => $department,
        'evaluated'  => $evaluated,
        'limit'      => $limit,
        'clusters'   => $clusters,
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
