<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;
use Gesseh\SimulationBundle\Entity\Simulation;
use Gesseh\CoreBundle\Entity\Placement;
use Gesseh\SimulationBundle\Entity\SimulPeriod;
use Gesseh\SimulationBundle\Form\SimulPeriodType;
use Gesseh\SimulationBundle\Form\SimulPeriodHandler;
use Gesseh\SimulationBundle\Entity\SectorRule;
use Gesseh\SimulationBundle\Form\SectorRuleType;
use Gesseh\SimulationBundle\Form\SectorRuleHandler;

/**
 * Simulation admin controller
 *
 * @Route("/admin/s")
 */
class SimulationAdminController extends Controller
{
    /**
     * @Route("/", name="GSimul_SAList")
     * @Template()
     */
    public function listAction()
    {
      $em = $this->getDoctrine()->getManager();
      $paginator = $this->get('knp_paginator');
      $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
      $simulations = $paginator->paginate($simulations_query, $this->get('request')->query->get('page', 1), 50);

      return array(
        'simulations' => $simulations,
      );
    }

    /**
     * @Route("/p", name="GSimul_SAPeriod")
     * @Template()
     */
    public function periodAction()
    {
      $em = $this->getDoctrine()->getManager();
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      return array(
        'periods'     => $periods,
        'period_id'   => null,
        'period_form' => null,
      );
    }

    /**
     * @Route("/p/n", name="GSimul_SANewPeriod")
     * @Template("GessehSimulationBundle:SimulationAdmin:period.html.twig")
     */
    public function newPeriodAction()
    {
      $em = $this->getDoctrine()->getManager();
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      $simul_period = new SimulPeriod();
      $form = $this->createForm(new SimulPeriodType(), $simul_period);
      $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" enregistrée.');
        return $this->redirect($this->generateUrl('GSimul_SAPeriod'));
      }

      return array(
        'periods'     => $periods,
        'period_id'   => null,
        'period_form' => $form->createView(),
      );
    }

    /**
     * @Route("/p/{id}/e", name="GSimul_SAEditPeriod", requirements={"id" = "\d+"})
     * @Template("GessehSimulationBundle:SimulationAdmin:period.html.twig")
     */
    public function editPeriodAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->find($id);

      if (!$simul_period)
        throw $this->createNotFoundException('Unable to find simul_period entity.');

      $form = $this->createForm(new SimulPeriodType(), $simul_period);
      $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" modifiée.');
        return $this->redirect($this->generateUrl('GSimul_SAPeriod'));
      }

      return array(
        'periods'     => $periods,
        'period_id'   => $id,
        'period_form' => $form->createView(),
      );
    }

    /**
     * @Route("/p/{id}/d", name="GSimul_SADeletePeriod", requirements={"id" = "\d+"})
     */
    public function deletePeriodAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->find($id);

      if (!$simul_period)
        throw $this->createNotFoundException('Unable to find simul_period entity.');

      $em->remove($simul_period);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" supprimée.');
      return $this->redirect($this->generateUrl('GSimul_SAPeriod'));
    }

    /**
     * @Route("/define", name="GSimul_SADefine")
     */
    public function defineAction()
    {
      $em = $this->getDoctrine()->getManager();
      $students = $em->getRepository('GessehUserBundle:Student')->getRankingOrder();
      $count = $em->getRepository('GessehSimulationBundle:Simulation')->setSimulationTable($students, $em);

      if($count) {
        $this->get('session')->getFlashBag()->add('notice', $count . ' étudiants enregistrés dans la table de simulation.');
      } else {
        $this->get('session')->getFlashBag()->add('error', 'Attention : Aucun étudiant enregistré dans la table de simulation.');
      }

      return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/sim", name="GSimul_SASim")
     */
    public function simAction()
    {
      $em = $this->getDoctrine()->getManager();
      $departments = $em->getRepository('GessehCoreBundle:Department')->findAll();

      foreach($departments as $department) {
          $department_table[$department->getId()] = $department->getNumber();
          if($department->getCluster() != null) {
              $department_table['cl_' . $department->getCluster()][] = $department->getId();
          }
      }

      $em->getRepository('GessehSimulationBundle:Simulation')->doSimulation($department_table, $em);

      $this->get('session')->getFlashBag()->add('notice', 'Les données de la simulation ont été actualisées.');
      return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/purge", name="GSimul_SAPurge")
     */
    public function purgeAction()
    {
      $em = $this->getDoctrine()->getManager();
//      $em->getRepository('GessehSimulationBundle:Simulation')->deleteAll();
      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->findAll();

      foreach($sims as $sim) {
        $em->remove($sim);
      }

      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', "Les données de la simulation ont été supprimées.");
      return $this->redirect($this->generateUrl('GSimul_SAList'));
    }

    /**
     * @Route("/save", name="GSimul_SASave")
     */
    public function saveAction()
    {
      $em = $this->getDoctrine()->getManager();

      if ($em->getRepository('GessehSimulationBundle:SimulPeriod')->isSimulationActive()) {
        $this->get('session')->getFlashBag()->add('error', 'La simulation est toujours active ! Vous ne pourrez la valider qu\'une fois qu\'elle sera inactive. Aucune donnée n\'a été copiée.');
        return $this->redirect($this->generateUrl('GSimul_SAList'));
      }

      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->getAllValid();
      $last_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->getLastActive();

      foreach($sims as $sim) {
        $placement = new Placement();
        $placement->setStudent($sim->getStudent());
        $placement->setDepartment($sim->getDepartment());
        $placement->setPeriod($last_period->getPeriod());
        $em->persist($placement);
      }

      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Les données de la simulation ont été copiées dans les stages.');
      return $this->redirect($this->generateUrl('GSimul_SAPurge'));
    }

    /**
     * Affiche un tableau de SectorRule
     *
     * @Route("/s/", name="GSimul_SARule")
     * @Template()
     */
    public function ruleAction()
    {
      $em = $this->getDoctrine()->getManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getAll();

      return array(
        'rules'     => $rules,
        'rule_form' => null,
      );
    }

    /**
     * Affiche un formulaire d'ajout de SectorRule
     *
     * @Route("/s/new", name="GSimul_SANewRule")
     * @Template("GessehSimulationBundle:SimulationAdmin:rule.html.twig")
     */
    public function newRuleAction()
    {
      $em = $this->getDoctrine()->getManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getAll();

      $sector_rule = new SectorRule();
      $form = $this->createForm(new SectorRuleType(), $sector_rule);
      $form_handler = new SectorRuleHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Relation entre "' . $sector_rule->getSector()->getName() . '" et "' . $sector_rule->getGrade()->getName() . '" ajoutée.');
        return $this->redirect($this->generateUrl('GSimul_SARule'));
      }

      return array(
        'rules'     => $rules,
        'rule_form' => $form->createView(),
      );
    }

    /**
     * Supprime un SectorRule
     *
     * @Route("/s/{id}/d", name="GSimul_SADeleteRule", requirements={"id" = "\d+"})
     */
    public function deleteRuleAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $rule = $em->getRepository('GessehSimulationBundle:SectorRule')->find($id);

      if (!$rule)
        throw $this->createNotFoundException('Unable to find sector_rule entity.');

      $em->remove($rule);
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Règle de simulation pour "' . $rule . '" supprimée.');
      return $this->redirect($this->generateUrl('GSimul_SARule'));
    }

    /**
     * Show the student's wishes
     *
     * @Route("/w/{id}", name="GSimul_SAWish", requirements={"id" = "\d+"})
     * @Template()
     */
    public function wishAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getSimStudent($id);
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->getForStudent($simstudent);
//      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($simstudent->getStudent());

      $new_wish = new Wish();
      $form = $this->createForm(new WishType($rules), $new_wish);
      $formHandler = new WishHandler($form, $this->get('request'), $em, $simstudent);

      if($formHandler->process()) {
        $this->get('session')->getFlashBag()->add('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');
        return $this->redirect($this->generateUrl('GSimul_SAWish', array('id' => $simstudent->getId())));
      }

      return array(
//        'wishes'     => $wishes,
        'wish_form'  => $form->createView(),
        'simstudent' => $simstudent,
      );
    }

    /**
     * Supprime un Wish
     *
     * @Route("/w/{id}/{wish}/d", name="GSimul_SADeleteWish", requirements={"id" = "\d+", "wish" = "\d+"})
     */
    public function deleteWishAction($id, $wish)
    {
      $em = $this->getDoctrine()->getManager();
      $simstudent = $em->getRepository('GessehSimulationBundle:Simulation')->getSimStudent($id);
      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getWishCluster($simstudent->getStudent(), $wish);

      if (!$wishes)
        throw $this->createNotFoundException('Unable to find wish entity.');

      foreach($wishes as $wish) {
        $rank = $wish->getRank();
        $wishes_after = $em->getRepository('GessehSimulationBundle:Wish')->findByRankAfter($simstudent->getStudent(), $rank);
        foreach($wishes_after as $wish_after) {
          $wish_after->setRank($wish_after->getRank()-1);
          $em->persist($wish_after);
        }
        $em->remove($wish);
      }

      if($simstudent->countWishes() <= 1) {
        $simstudent->setDepartment(null);
        $simstudent->setExtra(null);
        $em->persist($simstudent);
      }
      $em->flush();

      $this->get('session')->getFlashBag()->add('notice', 'Le vœu "' . $wish->getRank() . '" a été supprimé.');
      return $this->redirect($this->generateUrl('GSimul_SAWish', array('id' => $id)));
    }
}
