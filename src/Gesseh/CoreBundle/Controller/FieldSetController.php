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
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Entity\Hospital;

/**
 * FieldSet controller.
 */
class FieldSetController extends Controller
{
  /**
   * @Route("/f/", name="GCore_FSIndex")
   * @Route("/", name="homepage")
   * @Template()
   */
  public function indexAction()
  {
    $departments = $this->getDoctrine()->getManager()->getRepository('GessehCoreBundle:Department')->getAll();

    return array(
      'departments' => $departments
    );
  }

  /**
   * @Route("/f/{id}/d", name="GCore_FSShowDepartment", requirements={"id" = "\d+"})
   * @Template()
   */
  public function showDepartmentAction($id)
  {
    $department = $this->getDoctrine()->getManager()->getRepository('GessehCoreBundle:Department')->find($id);

    return array(
      'department' => $department
    );
  }

  /**
   * Finds and displays a Hospital entity.
   *
   * @Route("/f/{id}/h", name="GCore_FSShowHospital")
   * @Template()
   */
  public function showHospitalAction($id)
  {
    $hospital = $this->getDoctrine()->getManager()->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital) {
        throw $this->createNotFoundException('Unable to find Hospital entity.');
    }

    return array(
      'hospital' => $hospital,
    );
  }
}
