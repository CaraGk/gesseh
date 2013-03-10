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
    $em = $this->getDoctrine()->getEntityManager();
    $placements = $em->getRepository('GessehCoreBundle:Placement')->getByUsername($user);
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_eval = $manager->findParamByName('eval_active');

    if (true == $mod_eval->getValue()) { // si les évaluations sont activées
      $evaluated = $em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array', $user);
    }

    return array(
      'placements' => $placements,
      'evaluated'  => $evaluated,
    );
  }
}
