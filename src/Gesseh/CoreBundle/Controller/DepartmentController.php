<?php

namespace Gesseh\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\CoreBundle\Entity\Department;
use Gesseh\CoreBundle\Form\DepartmentType;
use Gesseh\CoreBundle\Form\DepartmentHandler;

class DepartmentController extends Controller
{
  /**
   * @Route("/dept", name="GessehCoreBundle_DepartmentIndex")
   * @Template()
   */
  public function indexAction()
  {
    $departments = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Department')->findAll();

    return array('departments' => $departments);
  }

  /**
   * @Route("/dept/{id}", name="GessehCoreBundle_DepartmentShow", requirements={"id" = "\d+"})
   * @Template()
   */
  public function showAction($id)
  {
    $department = $this->getDoctrine()->getEntityManager()->getRepository('GessehCoreBundle:Department')->find($id);

    return $this->render('GessehCoreBundle:Department:show.html.twig', array('department' => $department));
  }

  /**
   * @Route("/dept/create", name="GessehCoreBundle_DepartmentCreate")
   * @Template()
   */
  public function createAction()
  {
    $department  = new Department();
    $form    = $this->createForm(new DepartmentType(), $department);
    $formHandler = new DepartmentHandler($form, $this->get('request'), $this->getDoctrine()->getEntityManager());

    if ( $formHandler->process() )
      return $this->redirect($this->generateUrl('GessehCoreBundle_DepartmentShow', array('id' => $department->getId())));

    return $this->render('GessehCoreBundle:Department:create.html.twig', array(
      'entity' => $department,
      'form'   => $form->createView()
    ));
  }
}
