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
    $grades = $em->getRepository('GessehUserBundle:Grade')->findAll();

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
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
    $grades = $em->getRepository('GessehUserBundle:Grade')->findAll();

    $student = new Student();
    $form = $this->createForm(new StudentType(), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Étudiant "' . $student . '" enregistré.');
      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => $form->createView(),
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
    );
  }

  /**
   * @Route("/s/{id}/e", name="GUser_SAEditStudent", requirements={"id" = "\d+"})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function editStudentAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $students = $em->getRepository('GessehUserBundle:Student')->findAll();
    $grades = $em->getRepository('GessehUserBundle:Grade')->findAll();

    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Hospital entity.');

    $form = $this->createForm(new StudentType(), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Étudiant "' . $student . '" modifié.');
      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'students'     => $students,
      'student_id'   => $id,
      'student_form' => $form->createView(),
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
    );
  }

  /**
   * @Route("/s/{id}/d", name="GUser_SADeleteStudent")
   */
  public function deleteStudentAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $em->remove($student);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Etudiant "' . $student . '" supprimé.');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/s/{id}/pm", name="GUser_SAPromoteStudent")
   */
  public function promoteStudentAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    $user->addRole('ROLE_ADMIN');

    $um->updateUser($user);

    $this->get('session')->setFlash('notice', 'Droits d\'administration donnés à l\'étudiant "' . $student . '"');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/s/{id}/dm", name="GUser_SADemoteStudent")
   */
  public function demoteStudentAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    if( $user->hasRole('ROLE_ADMIN') )
      $user->removeRole('ROLE_ADMIN');
    $um->updateUser($user);

    $this->get('session')->setFlash('notice', 'Droits d\'administration retirés à l\'étudiant "' . $student . '"');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/g", name="GUser_SANewGrade")
   * @Template("GessehCoreBundle:StudentAdmin:index.html.twig")
   */
  public function newGradeAction($id)
  {
  }

  /**
   * @Route("/g/{id}/e", name="GUser_SAEditGrade", requirements={"id" = "\d+"})
   * @Template("GessehCoreBundle:StudentAdmin:index.html.twig")
   */
  public function editGradeAction($id)
  {
  }

  /**
   * @Route("/g/{id}/e", name="GUser_SADeleteGrade", requirements={"id" = "\d+"})
   */
  public function deleteGradeAction($id)
  {
  }
}
