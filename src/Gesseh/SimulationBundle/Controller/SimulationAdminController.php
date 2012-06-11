<?php

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;
use Gesseh\SimulationBundle\Entity\Simulation;

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

      return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
    }
}
