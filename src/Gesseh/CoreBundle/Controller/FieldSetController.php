<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2017 Pierre-François Angrand
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

    /* Affiche les terrains de stage sans accreditation si admin */
    if ($user and $user->hasRole('ROLE_ADMIN')) {
        $arg['admin'] = true;
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

    $hospitals = $em->getRepository('GessehCoreBundle:Hospital')->getAllWithDepartments($arg);
    $orphaneds = $em->getRepository('GessehCoreBundle:Hospital')->getAll();

    return array(
        'hospitals' => $hospitals,
        'sectors'   => $sectors,
        'limit'     => $arg['limit'],
        'orphaneds' => $orphaneds,
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
                'repartitions' => $em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndCluster($period, $cluster_name),
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
        $em = $this->getDoctrine()->getManager();
        /** Update database if some migrations are pending */
        if ($this->hasToMigrate($this->getDoctrine()->getConnection())) {
            $this->get('session')->getFlashBag()->add('notice', 'Mise à jour de la base de donnée effectuée.');

            /** Go to first user form if repository User is empty */
            if(!$em->getRepository('GessehUserBundle:User')->findAll()){
                return $this->redirect($this->generateUrl('GUser_SInstall'));
            }
        } else {
            $this->get('session')->getFlashBag()->add('notice', 'Toutes les mises à jour de la base de donnée ont déjà été effectuées.');
        }

        $parameters = $em->getRepository('GessehParameterBundle:Parameter')->findAll();
        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            $t = explode('_', $name);
            if (count($t) == 2) {
                $name = $t[0] . '_site_' . $t[1];
                $parameter->setName($name);
                $em->persist($parameter);
            } else {
                var_dump($t);
            }
        }


        $gateways = $em->getRepository('GessehRegisterBundle:Gateway')->findAll();
        foreach ($gateways as $gateway) {
            $name = $gateway->getGatewayName();
            $t = explode('_', $name);
            if (count($t) == 1) {
                $name = 'site_' . $t[0];
                $gateway->setGatewayName($name);
                $em->persist($gateway);
            } else {
                var_dump($t);
            }
        }

        $em->flush();

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
