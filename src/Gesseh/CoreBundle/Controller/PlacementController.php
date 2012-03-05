<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Placement;

/**
 * Placement controller.
 *
 * @Route("/u/p")
 */
class PlacementController extends Controller
{
  /**
   * @Route("/", name="GCore_PIndex")
   * @Template()
   */
  public function indexAction()
  {
    $user = $this->get('security.context')->getToken()->getUsername();
    $placements = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Placement')->getByUsername($user);

    return array(
      'placements' => $placements
    );
  }
}
