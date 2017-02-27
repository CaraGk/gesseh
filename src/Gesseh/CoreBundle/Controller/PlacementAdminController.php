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

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\CoreBundle\Entity\Period,
    Gesseh\CoreBundle\Form\PeriodType,
    Gesseh\CoreBundle\Form\PeriodHandler;
use Gesseh\CoreBundle\Entity\Placement,
    Gesseh\CoreBundle\Form\PlacementType,
    Gesseh\CoreBundle\Form\PlacementHandler;
use Gesseh\CoreBundle\Entity\Repartition,
    Gesseh\CoreBundle\Form\RepartitionsType,
    Gesseh\CoreBundle\Form\RepartitionsHandler;

/**
 * PlacementAdmin controller.
 *
 * @Route("/admin")
 */
class PlacementAdminController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

      /**
     * @Route("/period", name="GCore_PAPeriodIndex")
     * @Template()
     */
    public function periodAction()
    {
        $periods = $this->em->getRepository('GessehCoreBundle:Period')->findAll();

        return array(
            'periods'        => $periods,
            'period_id'      => null,
            'period_form'    => null,
        );
    }

    /**
     * @Route("/period/new", name="GCore_PAPeriodNew")
     * @Template("GessehCoreBundle:PlacementAdmin:period.html.twig")
     */
    public function newPeriodAction(Request $request)
    {
        $periods = $this->em->getRepository('GessehCoreBundle:Period')->findAll();
        $last_period = $this->em->getRepository('GessehCoreBundle:Period')->getLast();

        $period = new Period();
        $form = $this->createForm(new PeriodType(), $period);
        $formHandler = new PeriodHandler($form, $request, $this->em);

        if ( $formHandler->process() ) {
            $last_repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($last_period);
            foreach($last_repartitions as $repartition) {
                $new_repartition = new Repartition();
                $new_repartition->setPeriod($period);
                $new_repartition->setDepartment($repartition->getDepartment());
                $new_repartition->setNumber($repartition->getNumber());
                $new_repartition->setCluster($repartition->getCluster());
                $this->em->persist($new_repartition);
          }
          $this->em->flush();

          $this->session->getFlashBag()->add('notice', 'Session "' . $period . '" enregistrée.');

          return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
      }

      return array(
        'periods'        => $periods,
        'period_id'      => null,
        'period_form'    => $form->createView(),
      );
    }

    /**
     * @Route("/period/{id}/edit", name="GCore_PAPeriodEdit", requirements={"id" = "\d+"})
     * @Template("GessehCoreBundle:PlacementAdmin:period.html.twig")
     */
    public function editPeriodAction(Request $request, Period $period)
    {
      $paginator = $this->get('knp_paginator');
      $periods = $this->em->getRepository('GessehCoreBundle:Period')->findAll();

      $form = $this->createForm(new PeriodType(), $period);
      $formHandler = new PeriodHandler($form, $request, $this->em);

      if ( $formHandler->process() ) {
        $this->session->getFlashBag()->add('notice', 'Session "' . $period . '" modifiée.');

        return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
      }

      return array(
        'periods'        => $periods,
        'period_id'      => $id,
        'period_form'    => $form->createView(),
      );
    }

    /**
     * @Route("/period/{id}/delete", name="GCore_PAPeriodDelete", requirements={"id" = "\d+"})
     */
    public function deletePeriodeAction(Period $period)
    {
      $this->em->remove($period);
      $this->em->flush();

      $this->session->getFlashBag()->add('notice', 'Session "' . $period . '" supprimée.');

      return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
    }

    /**
     * @Route("/placement", name="GCore_PAPlacementIndex")
     * @Template()
     */
    public function placementAction(Request $request)
    {
      $limit = $request->query->get('limit', null);
      $paginator = $this->get('knp_paginator');
      $placements_query = $this->em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
      $placements = $paginator->paginate( $placements_query, $request->query->get('page', 1), 20);

      $manager = $this->container->get('kdb_parameters.manager');
      $mod_eval = $manager->findParamByName('eval_active');
      if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
        $evaluated = $this->em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
      } else {
          $evaluated = null;
      }

      return array(
        'placements'     => $placements,
        'placement_id'   => null,
        'placement_form' => null,
        'evaluated'      => $evaluated,
        'limit'          => $limit,
      );
    }

    /**
     * @Route("/placement/{id}/edit", name="GCore_PAPlacementEdit", requirements={"id" = "\d+"})
     * @Template("GessehCoreBundle:PlacementAdmin:placement.html.twig")
     */
    public function editPlacementAction(Request $request, Placement $placement)
    {
      $limit = $request->query->get('limit', null);
      $paginator = $this->get('knp_paginator');
      $placements_query = $this->em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
      $placements = $paginator->paginate( $placements_query, $request->query->get('page', 1), 20);

      $form = $this->createForm(new PlacementType(), $placement);
      $formHandler = new PlacementHandler($form, $request, $this->em);

      if ( $formHandler->process() ) {
        $this->session->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" modifié.');

        return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
      }

      $manager = $this->container->get('kdb_parameters.manager');
      $mod_eval = $manager->findParamByName('eval_active');
      if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
        $evaluated = $this->em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
      } else {
          $evaluated = null;
      }

      return array(
        'placements'     => $placements,
        'placement_id'   => $id,
        'placement_form' => $form->createView(),
        'evaluated'      => $evaluated,
        'limit'          => $limit,
      );
    }

    /**
     * @Route("/placement/new", name="GCore_PAPlacementNew")
     * @Template("GessehCoreBundle:PlacementAdmin:placement.html.twig")
     */
    public function newPlacementAction(Request $request)
    {
      $limit = $request->query->get('limit', null);
      $paginator = $this->get('knp_paginator');
      $placements_query = $this->em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
      $placements = $paginator->paginate( $placements_query, $request->query->get('page', 1), 20);

      $placement = new Placement();
      $form = $this->createForm(new PlacementType(), $placement);
      $formHandler = new PlacementHandler($form, $request, $this->em);

      if ( $formHandler->process() ) {
        $this->session->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" enregistré.');

        return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
      }

      $manager = $this->container->get('kdb_parameters.manager');
      $mod_eval = $manager->findParamByName('eval_active');
      if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
        $evaluated = $this->em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
      } else {
          $evaluated = null;
      }

      return array(
        'placements'     => $placements,
        'placement_id'   => null,
        'placement_form' => $form->createView(),
        'evaluated'      => $evaluated,
        'limit'          => $limit,
      );
    }

    /**
     * @Route("/placement/{id}/delete", name="GCore_PAPlacementDelete", requirements={"id" = "\d+"})
     */
    public function deletePlacementAction(Request $request, Placement $placement)
    {
      $limit = $request->query->get('limit', null);

      $this->em->remove($placement);
      $this->em->flush();

      $this->session->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" supprimé.');

      return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
    }

    /**
     * @Route("/period/{id}/repartitions", name="GCore_PARepartitionsPeriod", requirements={"id" = "\d+"})
     * @Template("GessehCoreBundle:PlacementAdmin:repartitionsEdit.html.twig")
     */
    public function repartitionsForPeriodEditAction(Request $request, Period $period)
    {
        $paginator = $this->get('knp_paginator');

        $hospital_id = $request->query->get('hospital_id', 0);
        $hospital_count = $request->query->get('hospital_count', 0);
        $next_hospital = $this->em->getRepository('GessehCoreBundle:Hospital')->getNext($hospital_id);
        $hospital_total = $this->em->getRepository('GessehCoreBundle:Hospital')->countAll();

        if (!$next_hospital)
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));

        $repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($period, $next_hospital->getId());

        $form = $this->createForm(new RepartitionsType($repartitions, 'period'), $repartitions);
        $form_handler = new RepartitionsHandler($form, $request, $this->em, $repartitions);
        if ($form_handler->process()) {
            $hospital_count;
            $this->session->getFlashBag()->add('notice', 'Répartition pour la période "' . $period . '" enregistrée (' . $hospital_count . '/' . $hospital_total . ').');

            return $this->redirect($this->generateUrl('GCore_PARepartitionsPeriod', array(
                'id'      => $period->getId(),
                'hospital_id'    => $next_hospital->getId(),
                'hospital_count' => $hospital_count,
            )));
        }

        return array(
            'origin' => $period->getName() . ' : ' . $next_hospital->getName(),
            'form'   => $form->createView(),
        );
    }

    /**
     * @Route("/department/{department_id}/repartitions", name="GCore_PARepartitionsDepartment", requirements={"department_id" = "\d+"})
     * @Template("GessehCoreBundle:PlacementAdmin:repartitionsEdit.html.twig")
     */
    public function repartitionsForDepartmentEditAction(Request $request, $department_id)
    {
        $department = $this->em->getRepository('GessehCoreBundle:Department')->find($department_id);

        if(!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $repartitions = $this->em->getRepository('GessehCoreBundle:Repartition')->getByDepartment($department_id);
        if (!$repartitions) {
            return $this->redirect($this->generateUrl('GCore_PARepartitionsDepartmentMaintenance', array(
                'department_id' => $department->getId(),
            )));
        }

        $form = $this->createForm(new RepartitionsType($repartitions, 'department'), $repartitions);
        $form_handler = new RepartitionsHandler($form, $request, $this->em, $repartitions);
        if ($form_handler->process()) {
            $this->session->getFlashBag()->add('notice', 'Répartition pour le terrain "' . $department . '" enregistrée.');

            return $this->redirect($this->generateUrl('GCore_FSIndex'));
        }

        return array(
            'origin'     => $department,
            'form' => $form->createView(),
        );
    }

    /**
     * Maintenance for repartitions
     *
     * @Route("/maintenance/repartition", name="GCore_PAMaintenanceRepartition")
     */
    public function maintenanceRepartitionAction(Request $request)
    {
        $periods = $this->em->getRepository('GessehCoreBundle:Period')->findAll();
        $department = $this->em->getRepository('GessehCoreBundle:Department')->getNext($request->query->get('department', 0));
        if (!$department) {
            $this->session->getFlashBag()->add('notice', 'Maintenance terminée !');
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
        }
        $count = 0;
        foreach ($periods as $period) {
            if (!$this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndDepartment($period, $department->getId())) {
                $repartition = new Repartition();
                $repartition->setDepartment($department);
                $repartition->setPeriod($period);
                $repartition->setNumber(0);
                $this->em->persist($repartition);
                $count++;
            }
        }
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Maintenance en cours : Terrain : ' . $department . ' = ' . $count . ' répartition(s) ajoutée(s)');
        return $this->redirect($this->generateUrl('GCore_PAMaintenanceRepartition', array('department' => $department->getId())));
    }

    /**
     * Maintenance for department's repartitions
     *
     * @Route("/department/{department_id}/repartitions/maintenance", name="GCore_PARepartitionsDepartmentMaintenance")
     */
    public function repartitionsForDepartmentMaintenanceAction($department_id)
    {
        $this->em = $this->getDoctrine()->getManager();
        $periods = $this->em->getRepository('GessehCoreBundle:Period')->findAll();
        $department = $this->em->getRepository('GessehCoreBundle:Department')->find($department_id);

        if(!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $count = 0;
        foreach ($periods as $period) {
            if (!$this->em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndDepartment($period, $department_id)) {
                $repartition = new Repartition();
                $repartition->setDepartment($department);
                $repartition->setPeriod($period);
                $repartition->setNumber(0);
                $this->em->persist($repartition);
                $count++;
            }
        }
        $this->em->flush();

        $this->session->getFlashBag()->add('notice', 'Maintenance : ' . $department . ' -> ' . $count . ' répartition(s) ajoutée(s)');
        return $this->redirect($this->generateUrl('GCore_PARepartitionsDepartment', array(
            'department_id' => $department->getId(),
        )));
    }
}
