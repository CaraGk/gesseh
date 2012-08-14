<?php

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Form\StudentType;
use Gesseh\UserBundle\Form\StudentHandler;
use Gesseh\UserBundle\Entity\Grade;
use Gesseh\UserBundle\Form\GradeType;
use Gesseh\UserBundle\Form\GradeHandler;

/**
 * StudentAdmin controller.
 *
 * @Route("/admin/u")
 */
class StudentAdminController extends Controller
{
  /**
   * @Route("/{page}", name="GUser_SAIndex", requirements={"page" = "\d+"}, defaults={"page" = 1})
   * @Template()
   */
  public function indexAction($page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students = $paginator->paginate( $students_query, $page, 20);
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
      'page'         => $page,
    );
  }

  /**
   * @Route("/{page}/s", name="GUser_SANewStudent", requirements={"page" = "\d+"}, defaults={"page" = 1})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function newStudentAction($page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students = $paginator->paginate( $students_query, $page, 20);
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = new Student();
    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Étudiant "' . $student . '" enregistré.');
      return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
    }

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => $form->createView(),
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
      'page'         => $page,
    );
  }

  /**
   * @Route("/{page}/s/{id}/e", name="GUser_SAEditStudent", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function editStudentAction($id, $page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students = $paginator->paginate( $students_query, $page, 20);
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Étudiant "' . $student . '" modifié.');
      return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
    }

    return array(
      'students'     => $students,
      'student_id'   => $id,
      'student_form' => $form->createView(),
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => null,
      'page'         => $page,
    );
  }

  /**
   * @Route("/{page}/s/{id}/d", name="GUser_SADeleteStudent", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   */
  public function deleteStudentAction($id, $page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $em->remove($student);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Etudiant "' . $student . '" supprimé.');
    return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
  }

  /**
   * @Route("/{page}/s/{id}/pm", name="GUser_SAPromoteStudent", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   */
  public function promoteStudentAction($id, $page)
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
    return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
  }

  /**
   * @Route("/{page}/s/{id}/dm", name="GUser_SADemoteStudent", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   */
  public function demoteStudentAction($id, $page)
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
    return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
  }

  /**
   * @Route("/{page}/g", name="GUser_SANewGrade", requirements={"page" = "\d+"}, defaults={"page" = 1})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function newGradeAction($page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students = $paginator->paginate( $students_query, $page, 20);
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    $grade = new Grade();
    $grade->setRank($em->getRepository('GessehUserBundle:Grade')->getLastActiveRank() + 1);
    $form = $this->createForm(new GradeType(), $grade);
    $formHandler = new GradeHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Promotion "' . $grade . '" enregistrée.');
      return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
    }

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
      'grades'       => $grades,
      'grade_id'     => null,
      'grade_form'   => $form->createView(),
      'page'         => $page,
    );
  }

  /**
   * @Route("/{page}/g/{id}/e", name="GUser_SAEditGrade", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function editGradeAction($id, $page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students = $paginator->paginate( $students_query, $page, 20);
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAll();

    $grade = $em->getRepository('GessehUserBundle:Grade')->find($id);

    if( !$grade )
      throw $this->createNotFoundException('Unable to find Grade entity.');

    $form = $this->createForm(new GradeType(), $grade);
    $formHandler = new GradeHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Promotion "' . $grade . '" modifiée.');
      return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
    }

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
      'grades'       => $grades,
      'grade_id'     => $id,
      'grade_form'   => $form->createView(),
      'page'         => $page,
    );
  }

  /**
   * @Route("/{page}/g/{id}/d", name="GUser_SADeleteGrade", requirements={"id" = "\d+", "page" = "\d+"}, defaults={"page" = 1})
   */
  public function deleteGradeAction($id, $page)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $grade = $em->getRepository('GessehUserBundle:Grade')->find($id);

    if( !$grade )
      throw $this->createNotFoundException('Unable to find Grade entity.');

    $em->remove($grade);
    $em->flush();

    $this->get('session')->setFlash('notice', 'Promotion "' . $grade . '" supprimée.');
    return $this->redirect($this->generateUrl('GUser_SAIndex', array('page' => $page)));
  }

  /**
   * @Route("/u/upd-grade", name="GUser_SAUpdateGrade")
   */
  public function updateGradeAction()
  {
    $em = $this->getDoctrine()->getEntityManager();
    $grades = $em->getRepository('GessehUserBundle:Grade')->getAllActiveInverted();

    foreach( $grades as $grade ) {
      $next_grade = $em->getRepository('GessehUserBundle:Grade')->getNext($grade->getRank());
      if( null !== $next_grade ) {
        $em->getRepository('GessehUserBundle:Student')->setGradeUp($grade->getId(), $next_grade->getId());
      }
    }

    $this->get('session')->setFlash('notice', 'Étudiants passés dans la promotion supérieure.');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }
}
