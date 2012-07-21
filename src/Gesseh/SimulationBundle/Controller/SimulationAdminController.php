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
use Gesseh\SimulationBundle\Entity\SimulPeriod;
use Gesseh\SimulationBundle\Form\SimulPeriodType;
use Gesseh\SimulationBundle\Form\SimulPeriodHandler;
use Gesseh\SimulationBundle\Entity\SectorRule;
use Gesseh\SimulationBundle\Form\SectorRuleType;
use Gesseh\SimulationBundle\Form\SectorRuleHandler;

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
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      return array(
        'simulations' => $simulations,
        'periods'     => $periods,
        'period_id'   => null,
        'period_form' => null,
      );
    }

    /**
     * @Route("/p/n", name="GSimul_SANewPeriod")
     * @Template("GessehSimulationBundle:SimulationAdmin:index.html.twig")
     */
    public function newPeriod()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $paginator = $this->get('knp_paginator');
      $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
      $simulations = $paginator->paginate($simulations_query, $this->get('request')->query->get('page', 1), 50);
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      $simul_period = new SimulPeriod();
      $form = $this->createForm(new SimulPeriodType(), $simul_period);
      $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->setFlash('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" enregistrée.');
        return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
      }

      return array(
        'simulations' => $simulations,
        'periods'     => $periods,
        'period_id'   => null,
        'period_form' => $form->createView(),
      );
    }

    /**
     * @Route("/p/{id}/e", name="GSimul_SAEditPeriod", requirements={"id" = "\d+"})
     * @Template("GessehSimulationBundle:SimulationAdmin:index.html.twig")
     */
    public function editPeriod($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $paginator = $this->get('knp_paginator');
      $simulations_query = $em->getRepository('GessehSimulationBundle:Simulation')->getAll();
      $simulations = $paginator->paginate($simulations_query, $this->get('request')->query->get('page', 1), 50);
      $periods = $em->getRepository('GessehSimulationBundle:SimulPeriod')->findAll();

      $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->find($id);

      if (!$simul_period)
        throw $this->createNotFoundException('Unable to find simul_period entity.');

      $form = $this->createForm(new SimulPeriodType(), $simul_period);
      $form_handler = new SimulPeriodHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->setFlash('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" modifiée.');
        return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
      }

      return array(
        'simulations' => $simulations,
        'periods'     => $periods,
        'period_id'   => $id,
        'period_form' => $form->createView(),
      );
    }

    /**
     * @Route("/p/{id}/d", name="GSimul_SADeletePeriod", requirements={"id" = "\d+"})
     */
    public function deletePeriod($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $simul_period = $em->getRepository('GessehSimulationBundle:SimulPeriod')->find($id);

      if (!$simul_period)
        throw $this->createNotFoundException('Unable to find simul_period entity.');

      $em->remove($simul_period);
      $em->flush();

      $this->get('session')->setFlash('notice', 'Session de simulations du "' . $simul_period->getBegin()->format('d-m-Y') . '" au "' . $simul_period->getEnd()->format('d-m-Y') . '" supprimée.');
      return $this->redirect($this->generateUrl('GSimulation_SAIndex'));
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
      $sims = $em->getRepository('GessehSimulationBundle:Simulation')->findAll();
      $last_period = $em->getRepository('GessehCoreBundle:Period')->findLast(); /** utiliser active_period plutôt que last_period ? */

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

    /**
     * Affiche un tableau de SectorRule
     *
     * @Route("/s/", name="GSimul_SAIndexRule")
     * @Template()
     */
    public function indexRuleAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->findAll();

      return array(
        'rules'     => $rules,
        'rule_form' => null,
      );
    }

    /**
     * Affiche un formulaire d'ajout de SectorRule
     *
     * @Route("/s/new", name="GSimul_SANewRule")
     * @Template("GessehSimulationBundle:SimulationAdmin:indexRule.html.twig")
     */
    public function newRuleAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $rules = $em->getRepository('GessehSimulationBundle:SectorRule')->findAll();

      $sector_rule = new SectorRule();
      $form = $this->createForm(new SectorRuleType(), $sector_rule);
      $form_handler = new SectorRuleHandler($form, $this->get('request'), $em);

      if ($form_handler->process()) {
        $this->get('session')->setFlash('notice', 'Relation entre "' . $sector_rule->getSector()->getName() . '" et "' . $sector_rule->getGrade()->getName() . '" ajoutée.');
        return $this->redirect($this->generateUrl('GSimul_SAIndexRule'));
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
    public function deleteRule($id)
    {
      $em = $this->getDoctrine()->getEntityManager();
      $rule = $em->getRepository('GessehSimulationBundle:SectorRule')->find($id);

      if (!$rule)
        throw $this->createNotFoundException('Unable to find sector_rule entity.');

      $em->remove($rule);
      $em->flush();

      $this->get('session')->setFlash('notice', 'Règle de simulation pour "' . $rule . '" supprimée.');
      return $this->redirect($this->generateUrl('GSimul_SAIndexRule'));
    }
}
