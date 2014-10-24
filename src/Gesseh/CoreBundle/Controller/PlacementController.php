<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Placement;

/**
 * Placement controller.
 *
 * @Route("/placement")
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
    $em = $this->getDoctrine()->getManager();
    $placements = $em->getRepository('GessehCoreBundle:Placement')->getByUsername($user);
    $pm = $this->container->get('kdb_parameters.manager');

    if (true == $pm->findParamByName('eval_active')->getValue()) {
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array', $user);
    } else {
        $evaluated = array();
    }

    return array(
      'placements' => $placements,
      'evaluated'  => $evaluated,
    );
  }
}
