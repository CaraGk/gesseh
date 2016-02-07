<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
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
 * @Route("/admin/simulation")
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
//      $simulations = $paginator->paginate($simulations_query, $this->get('request')->query->get('page', 1), 50);
      $simul_missing = $em->getRepository('GessehSimulationBundle:Simulation')->countMissing();
      $simul_total = $em->getRepository('GessehSimulationBundle:Simulation')->countTotal();

      return array(
          'simulations'   => $simulations_query->getResult(),
          'simul_missing' => $simul_missing,
          'simul_total'   => $simul_total,
      );
    }

    /**
     * @Route("/period/simul/{id}", name="GSimul_SAPeriod", requirements={"id" = "\d+"})
     * @Template()
     */
    public function periodAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $period = $em->getRepository('GessehCoreBundle:Period')->find($id);
        $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findOneByPeriod($id);

        if (!$simul_period) {
            $simul_period = new SimulPeriod();
            $simul_period->setPeriod($period);
        }

        $form = $this->createForm(new SimulPeriodType(), $simul_period);
        $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em, $period);

        if ($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" modifiée.');
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
        }

        return array(
            'period'      => $period,
            'period_form' => $form->createView(),
            'simul_period'=> $simul_period,
        );
    }

    /**
     * @Route("/period/simul/{id}/delete", name="GSimul_SAPeriodDelete", requirements={"id" = "\d+"})
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

      return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
    }

    /**
     * @Route("/define", name="GSimul_SADefine")
     */
    public function defineAction()
    {
      $em = $this->getDoctrine()->getManager();
      $students = $em->getRepository('GessehUserBundle:Student')->getRankingOrder();
      $count = $em->getRepository('GessehSimulationBundle:Simulation')->setSimulationTable($students, $em);

      if ($count) {
        $this->get('session')->getFlashBag()->add('notice', $count . ' étudiants enregistrés dans la table de simulation.');
      } else {
        $this->get('session')->getFlashBag()->add('error', 'Attention : Aucun étudiant enregistré dans la table de simulation.');
      }

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

      foreach ($sims as $sim) {
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

      foreach ($sims as $sim) {
        if ($sim->getDepartment()->getCluster() != null) {
            $clusters = $em->getRepository('GessehCoreBundle:Department')->getAllCluster($sim->getDepartment()->getId());

            foreach ($clusters as $cluster) {
                $placement = new Placement();
                $placement->setStudent($sim->getStudent());
                $placement->setDepartment($cluster);
                $placement->setPeriod($last_period->getPeriod());
                $em->persist($placement);
            }
        } else {
            $placement = new Placement();
            $placement->setStudent($sim->getStudent());
            $placement->setDepartment($sim->getDepartment());
            $placement->setPeriod($last_period->getPeriod());
            $em->persist($placement);
        }
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
}
