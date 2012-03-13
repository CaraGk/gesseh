<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Period;
use Gesseh\CoreBundle\Form\PeriodType;
use Gesseh\CoreBundle\Form\PeriodHandler;
use Gesseh\CoreBundle\Entity\Placement;
use Gesseh\CoreBundle\Form\PlacementType;
use Gesseh\CoreBundle\Form\PlacementHandler;

/**
 * PlacementAdmin controller.
 *
 * @Route("/admin/p")
 */
class PlacementAdminController extends Controller
{
  /**
   * @Route("/", name="GCore_PAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'periods'        => $periods,
      'period_id'      => null,
      'period_form'    => null,
      'placements'     => $placements,
      'placement_id'   => null,
      'placement_form' => null,
    );
  }

  /**
   * @Route("/{id}/student", name="GCore_PAIndexStudent", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function indexStudentAction($id)
  {
  }

  /**
   * @Route("/{id}/department", name="GCore_PAIndexDepartment", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function indexDepartmentAction($id)
  {
  }

  /**
   * @Route("/{id}/e", name="GCore_PAEditPlacement", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function editPlacementAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

    if( !$placement )
      throw $this->createNotFoundException('Unable to find Placement entity.');

    $form = $this->createForm(new PlacementType(), $placement);
    $formHandler = new PlacementHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getDepartment() . $placement->getPeriod() . '" modifié.');
      return $this->redirect($this->generateUrl('GCore_PAIndex'));
    }

    return array(
      'periods'        => $periods,
      'period_id'      => null,
      'period_form'    => null,
      'placements'     => $placements,
      'placement_id'   => $id,
      'placement_form' => $form->createView(),
    );
  }

  /**
   * @Route("/n", name="GCore_PANewPlacement")
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function newPlacementAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $placement = new Placement();
    $form = $this->createForm(new PlacementType(), $placement);
    $formHandler = new PlacementHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getDepartment() . $placement->getPeriod() . '" enregistré.');
      return $this->redirect($this->generateUrl('GCore_PAIndex'));
    }

    return array(
      'periods'        => $periods,
      'period_id'      => null,
      'period_form'    => null,
      'placements'     => $placements,
      'placement_id'   => null,
      'placement_form' => $form->createView(),
    );
  }

  /**
   * @Route("/{id}/d", name="GCore_PADeletePlacement", requirements={"id" = "\d+"})
   */
  public function deletePlacementAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $placement = $em->getRepository('GessehCoreBundle:Placement')->find($id);

    if( !$placement )
      throw $this->createNotFoundException('Unable to find Placement entity.');

    $em->remove($placement);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Stage "' . $placement->getStudent() . ' : ' . $placement->getDepartment() . $placement->getPeriod() . '" supprimé.');
    return $this->redirect($this->generateUrl('GCore_PAIndex'));
  }

  /**
   * @Route("/p/n", name="GCore_PANewPeriod")
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function newPeriodAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $period = new Period();
    $form = $this->createForm(new PeriodType(), $period);
    $formHandler = new PeriodHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Session "Du' . $period->getBegin()->format('d-m-Y') . ' au ' . $period->getEnd()->format('d-m-Y') . '" enregistrée.');
      return $this->redirect($this->generateUrl('GCore_PAIndex'));
    }

    return array(
      'periods'        => $periods,
      'period_id'      => null,
      'period_form'    => $form->createView(),
      'placements'     => $placements,
      'placement_id'   => null,
      'placement_form' => null,
    );
  }

  /**
   * @Route("/p/{id}/e", name="GCore_PAEditPeriod", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function editPeriodAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $periods = $em->getRepository('GessehCoreBundle:Period')->findAll();
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    $period = $em->getRepository('GessehCoreBundle:Period')->find($id);

    if( !$period )
      throw $this->createNotFoundException('Unable to find period entity.');

    $form = $this->createForm(new PeriodType(), $period);
    $formHandler = new PeriodHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Session "' . $period . '" modifiéeée.');
      return $this->redirect($this->generateUrl('GCore_PAIndex'));
    }

    return array(
      'periods'        => $periods,
      'period_id'      => $id,
      'period_form'    => $form->createView(),
      'placements'     => $placements,
      'placement_id'   => null,
      'placement_form' => null,
    );
  }

  /**
   * @Route("/p/{id}/d", name="GCore_PADeletePeriod", requirements={"id" = "\d+"})
   */
  public function deletePeriodeAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $period = $em->getRepository('GessehCoreBundle:Period')->find($id);

    if( !$period )
      throw $this->createNotFoundException('Unable to find period entity.');

    $em->remove($period);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Session "' . $period . '" supprimée.');
    return $this->redirect($this->generateUrl('GCore_PAIndex'));
  }
}
