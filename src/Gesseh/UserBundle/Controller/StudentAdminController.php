<?php

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Form\StudentType;
use Gesseh\UserBundle\Form\StudentHandler;

/**
 * StudentAdmin controller.
 *
 * @Route("/admin/u")
 */
class StudentAdminController extends Controller
{
  /**
   * @Route("/", name="GUser_SAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $students = $em->getRepository('GessehUserBundle:Student')->findAll();

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
    );
  }

  /**
   * @Route("/s", name="GUser_SANewStudent")
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function newStudentAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $students = $em->getRepository('GessehUserBundle:Student')->findAll();

    $student = new Student();
    $form = $this->createForm(new StudentType(), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Étudiant "' . $student . ' enregistré.');
      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'students' => $students,
      'student_id' => null,
      'student_form' => $form->createView(),
    );
  }
}
