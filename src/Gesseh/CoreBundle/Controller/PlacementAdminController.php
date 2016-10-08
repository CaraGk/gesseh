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

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
  /**
   * @Route("/period", name="GCore_PAPeriodIndex")
   * @Template()
   */
  public function periodAction()
  {
    $em = $this->getDoctrine()->getManager();
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();

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
  public function newPeriodAction()
  {
    $em = $this->getDoctrine()->getManager();
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $last_period = $em->getRepository('GessehCoreBundle:Period')->getLast();

    $period = new Period();
    $form = $this->createForm(new PeriodType(), $period);
    $formHandler = new PeriodHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
        $last_repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($last_period);
        foreach($last_repartitions as $repartition) {
            $new_repartition = new Repartition();
            $new_repartition->setPeriod($period);
            $new_repartition->setDepartment($repartition->getDepartment());
            $new_repartition->setNumber($repartition->getNumber());
            $new_repartition->setCluster($repartition->getCluster());
            $em->persist($new_repartition);
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Session "' . $period . '" enregistrée.');

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
  public function editPeriodAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();

    $period = $em->getRepository('GessehCoreBundle:Period')->find($id);

    if( !$period )
      throw $this->createNotFoundException('Unable to find period entity.');

    $form = $this->createForm(new PeriodType(), $period);
    $formHandler = new PeriodHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Session "' . $period . '" modifiée.');

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
  public function deletePeriodeAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $period = $em->getRepository('GessehCoreBundle:Period')->find($id);

    if( !$period )
      throw $this->createNotFoundException('Unable to find period entity.');

    $em->remove($period);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Session "' . $period . '" supprimée.');

    return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
  }

  /**
   * @Route("/placement", name="GCore_PAPlacementIndex")
   * @Template()
   */
  public function placementAction()
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $paginator = $this->get('knp_paginator');
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $manager = $this->container->get('kdb_parameters.manager');
    $mod_eval = $manager->findParamByName('eval_active');
    if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
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
  public function editPlacementAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $paginator = $this->get('knp_paginator');
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

    if( !$placement )
      throw $this->createNotFoundException('Unable to find Placement entity.');

    $form = $this->createForm(new PlacementType(), $placement);
    $formHandler = new PlacementHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" modifié.');

      return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
    }

    $manager = $this->container->get('kdb_parameters.manager');
    $mod_eval = $manager->findParamByName('eval_active');
    if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
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
  public function newPlacementAction()
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $paginator = $this->get('knp_paginator');
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll($limit);
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $placement = new Placement();
    $form = $this->createForm(new PlacementType(), $placement);
    $formHandler = new PlacementHandler($form, $this->get('request'), $em);

    if ( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" enregistré.');

      return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
    }

    $manager = $this->container->get('kdb_parameters.manager');
    $mod_eval = $manager->findParamByName('eval_active');
    if (true == $mod_eval->getValue()) { // Si les évaluations sont activées
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array');
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
  public function deletePlacementAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $limit = $this->get('request')->query->get('limit', null);
    $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

    if( !$placement )
      throw $this->createNotFoundException('Unable to find Placement entity.');

    $em->remove($placement);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getRepartition()->getDepartment() . $placement->getRepartition()->getPeriod() . '" supprimé.');

    return $this->redirect($this->generateUrl('GCore_PAPlacementIndex'));
  }

    /**
     * @Route("/period/{period_id}/repartitions", name="GCore_PARepartitionsPeriod", requirements={"period_id" = "\d+"})
     * @Template("GessehCoreBundle:PlacementAdmin:repartitionsEdit.html.twig")
     */
    public function repartitionsForPeriodEditAction(Period $period)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');

        $hospital_id = $this->getRequest()->query->get('hospital_id', 0);
        $hospital_count = $this->getRequest()->query->get('hospital_count', 0);
        $next_hospital = $em->getRepository('GessehCoreBundle:Hospital')->getNext($hospital_id);
        $hospital_total = $em->getRepository('GessehCoreBundle:Hospital')->countAll();

        if (!$next_hospital)
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));

        $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByPeriod($period, $next_hospital->getId());

        $form = $this->createForm(new RepartitionsType($repartitions, 'period'), $repartitions);
        $form_handler = new RepartitionsHandler($form, $this->get('request'), $em, $repartitions);
        if ($form_handler->process()) {
            $hospital_count;
            $this->get('session')->getFlashBag()->add('notice', 'Répartition pour la période "' . $period . '" enregistrée (' . $hospital_count . '/' . $hospital_total . ').');

            return $this->redirect($this->generateUrl('GCore_PARepartitionsPeriod', array(
                'period_id'      => $period_id,
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
    public function repartitionsForDepartmentEditAction($department_id)
    {
        $em = $this->getDoctrine()->getManager();
        $department = $em->getRepository('GessehCoreBundle:Department')->find($department_id);

        if(!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $repartitions = $em->getRepository('GessehCoreBundle:Repartition')->getByDepartment($department_id);
        if (!$repartitions) {
            return $this->redirect($this->generateUrl('GCore_PARepartitionsDepartmentMaintenance', array(
                'department_id' => $department->getId(),
            )));
        }

        $form = $this->createForm(new RepartitionsType($repartitions, 'department'), $repartitions);
        $form_handler = new RepartitionsHandler($form, $this->get('request'), $em, $repartitions);
        if ($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Répartition pour le terrain "' . $department . '" enregistrée.');

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
    public function maintenanceRepartitionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
        $department = $em->getRepository('GessehCoreBundle:Department')->getNext($this->get('request')->query->get('department', 0));
        if (!$department) {
            $this->get('session')->getFlashBag()->add('notice', 'Maintenance terminée !');
            return $this->redirect($this->generateUrl('GCore_PAPeriodIndex'));
        }
        $count = 0;
        foreach ($periods as $period) {
            if (!$em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndDepartment($period, $department->getId())) {
                $repartition = new Repartition();
                $repartition->setDepartment($department);
                $repartition->setPeriod($period);
                $repartition->setNumber(0);
                $em->persist($repartition);
                $count++;
            }
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Maintenance en cours : Terrain : ' . $department . ' = ' . $count . ' répartition(s) ajoutée(s)');
        return $this->redirect($this->generateUrl('GCore_PAMaintenanceRepartition', array('department' => $department->getId())));
    }

    /**
     * Maintenance for department's repartitions
     *
     * @Route("/department/{department_id}/repartitions/maintenance", name="GCore_PARepartitionsDepartmentMaintenance")
     */
    public function repartitionsForDepartmentMaintenanceAction($department_id)
    {
        $em = $this->getDoctrine()->getManager();
        $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
        $department = $em->getRepository('GessehCoreBundle:Department')->find($department_id);

        if(!$department)
            throw $this->createNotFoundException('Unable to find department entity.');

        $count = 0;
        foreach ($periods as $period) {
            if (!$em->getRepository('GessehCoreBundle:Repartition')->getByPeriodAndDepartment($period, $department_id)) {
                $repartition = new Repartition();
                $repartition->setDepartment($department);
                $repartition->setPeriod($period);
                $repartition->setNumber(0);
                $em->persist($repartition);
                $count++;
            }
        }
        $em->flush();

        $this->get('session')->getFlashBag()->add('notice', 'Maintenance : ' . $department . ' -> ' . $count . ' répartition(s) ajoutée(s)');
        return $this->redirect($this->generateUrl('GCore_PARepartitionsDepartment', array(
            'department_id' => $department->getId(),
        )));
    }
}
