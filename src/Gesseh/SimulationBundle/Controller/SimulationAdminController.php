<?php

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;
use Gesseh\SimulationBundle\Entity\Simulation;
use Gesseh\CoreBundle\Entity\Placement;

/**
 * @Route("/admin/s")
 */
class SimulationAdminController extends Controller
{
    /**
     * @Route("/", name="GSimulation_SAIndex")
     * @Template()
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $paginator = $this->get('knp_paginator');
      $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
      $simulations = $paginator->paginate($simulations_query, $this->get('request')->query->get('page', 1), 50);

      return array(
        'simulations' => $simulations,
      );
    }

    /**
     * @Route("/define", name="GSimulation_SADefine")
     */
    public function defineAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $students = $em->getRepository('GessehUserBundle:Student')->getRankingOrder();
      $count = $em->getRepository('GessehSimulationBundle:Simulation')->setSimulationTable($students, $em);

      if($count) {
        $this->get('session')->setFlash('notice', $count . ' étudiants enregistrés dans la table de simulation.');
      } else {
        $this->get('session')->setFlash('error', 'Attention : Aucun étudiant enregistré dans la table de simulation.');
      }

      return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
    }

    /**
     * @Route("/sim", name="GSimulation_SASim")
     */
    public function simAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $departments = $em->getRepository('GessehCoreBundle:Department')->findAll();

      foreach($departments as $department) {
        $department_table[$department->getId()] = $department->getNumber();
      }

      $em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $em);

      $this->get('session')->setFlash('notice', 'Les données de la simulation ont été actualisées');
      return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
    }

    /**
     * @Route("/purge", name="GSimulation_SAPurge")
     */
    public function purgeAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
//      $em->getRepository('GessehSimulationBundle:Simulation')->deleteAll();
      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->findAll();

      foreach($sims as $sim) {
        $em->remove($sim);
      }

      $em->flush();

      $this->get('session')->setFlash('notice', 'Les données de la simulation ont été supprimées.');
      return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
    }

    /**
     * @Route("/save", name="GSimulation_SASave")
     */
    public function saveAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $em->getRepository('GessehSimulationBundle:Simulation')->findAll();
      $last_period = $em->getRepository('GessehCoreBundle:Period')->findLast();

      foreach($sims as $sim) {
        $placement = new Placement();
        $placement->setStudent($sim->getStudent());
        $placement->setDepartment($sim->getDepartment());
        $placement->setPeriode($last_period);
        $em->persist($placement);
      }

      $em->flush();

      $this->get('session')->setFlash('notice', 'Les données de la simulation ont été copiées dans les stages.');
      return $this->redirect($this->generateUrl('GSimulation_SAPurge'));
    }
}
