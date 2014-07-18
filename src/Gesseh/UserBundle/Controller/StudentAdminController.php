<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

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
 * @Route("/admin/student")
 */
class StudentAdminController extends Controller
{
  /**
   * @Route("/", name="GUser_SAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students_count = $em->getRepository('GessehUserBundle:Student')->countAll();
    $students = $paginator->paginate( $students_query, $this->get('request')->query->get('page', 1), 20);

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => null,
      'students_count' => $students_count,
    );
  }

  /**
   * @Route("/new", name="GUser_SANew")
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function newAction()
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students_count = $em->getRepository('GessehUserBundle:Student')->countAll();
    $students = $paginator->paginate( $students_query, $this->get('request')->query->get('page', 1), 20);
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = new Student();
    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Étudiant "' . $student . '" enregistré.');
      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'students'     => $students,
      'student_id'   => null,
      'student_form' => $form->createView(),
      'students_count'=> $students_count,
    );
  }

  /**
   * @Route("/{id}/edit", name="GUser_SAEdit", requirements={"id" = "\d+"})
   * @Template("GessehUserBundle:StudentAdmin:index.html.twig")
   */
  public function editAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $paginator = $this->get('knp_paginator');
    $students_query = $em->getRepository('GessehUserBundle:Student')->getAll();
    $students_count = $em->getRepository('GessehUserBundle:Student')->countAll();
    $students = $paginator->paginate( $students_query, $this->get('request')->query->get('page', 1), 20);
    $manager = $this->container->get('kdb_parameters.manager');
    $mod_simul = $manager->findParamByName('simul_active');

    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $form = $this->createForm(new StudentType($mod_simul->getValue()), $student);
    $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

    if( $formHandler->process() ) {
      $this->get('session')->getFlashBag()->add('notice', 'Étudiant "' . $student . '" modifié.');
      return $this->redirect($this->generateUrl('GUser_SAIndex'));
    }

    return array(
      'students'     => $students,
      'student_id'   => $id,
      'student_form' => $form->createView(),
      'students_count'=> $students_count,
    );
  }

  /**
   * @Route("/{id}/delete", name="GUser_SADelete", requirements={"id" = "\d+"})
   */
  public function deleteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $em->remove($student);
    $em->flush();

    $this->get('session')->getFlashBag()->add('notice', 'Etudiant "' . $student . '" supprimé.');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/{id}/promote", name="GUser_SAPromote", requirements={"id" = "\d+"})
   */
  public function promoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    $user->addRole('ROLE_ADMIN');

    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration donnés à l\'étudiant "' . $student . '"');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

  /**
   * @Route("/{id}/demote", name="GUser_SADemote", requirements={"id" = "\d+"})
   */
  public function demoteAction($id)
  {
    $em = $this->getDoctrine()->getManager();
    $um = $this->container->get('fos_user.user_manager');
    $student = $em->getRepository('GessehUserBundle:Student')->find($id);

    if( !$student )
      throw $this->createNotFoundException('Unable to find Student entity.');

    $user = $student->getUser();
    if( $user->hasRole('ROLE_ADMIN') )
      $user->removeRole('ROLE_ADMIN');
    $um->updateUser($user);

    $this->get('session')->getFlashBag()->add('notice', 'Droits d\'administration retirés à l\'étudiant "' . $student . '"');
    return $this->redirect($this->generateUrl('GUser_SAIndex'));
  }

}
