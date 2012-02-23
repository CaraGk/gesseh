<?php

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
   * @Template()
   */
  public function indexAction()
  {
    $departments = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Department')->findAll();

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
    $department = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Department')->find($id);

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
    $hospital = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Hospital')->find($id);

    if (!$hospital) {
        throw $this->createNotFoundException('Unable to find Hospital entity.');
    }

    return array(
      'hospital' => $hospital,
    );
  }
}
