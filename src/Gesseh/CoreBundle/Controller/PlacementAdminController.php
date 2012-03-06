<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
    $placements_query = $em->getRepository('GessehCoreBundle:Placement')->getAll();
    $placements = $paginator->paginate( $placements_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'placements'     => $placements,
      'placement_id'   => null,
      'placement_form' => null,
    );
  }

  /**
   * @Route("/{id}/e", name="GCore_PAEditPlacement", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:PlacementAdmin:index.html.twig")
   */
  public function editPlacementAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
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
      'placements'     => $placements,
      'placement_id'   => $id,
      'placement_form' => $form->createForm(),
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
      'placements' => $placements,
      'placement_id' => null,
      'placement_form' => $form->createView(),
    );
  }

  /**
   * @Route("/{id}/d", name="GCore_PADeletePlacement")
   */
  public function deletePlacementAction($id)
  {
  }
}
