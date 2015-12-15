<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\UserBundle\Entity\Student;
use Gesseh\UserBundle\Form\StudentUserType;
use Gesseh\UserBundle\Form\StudentHandler;
use Gesseh\UserBundle\Form\StudentFirstType;

/**
 * Student controller.
 *
 * @Route("/")
 */
class StudentController extends Controller
{
    /**
     * @Route("/user/edit", name="GUser_SEdit")
     * @Template()
     */
    public function editAction()
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->get('security.token_storage')->getToken()->getUsername();
      $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($user);

      if( !$student )
        throw $this->createNotFoundException('Unable to find Student entity.');

      $form = $this->createForm(new StudentUserType(), $student);
      $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if ( $formHandler->process() ) {
        $this->get('session')->getFlashBag()->add('notice', 'Votre compte a bien été modifié.');

        return $this->redirect($this->generateUrl('GUser_SEdit'));
      }

      return array(
        'form' => $form->createView(),
      );
    }

    /**
     * Install first user
     *
     * @Route("/firstuser", name="GUser_SInstall")
     * @Template("GessehUserBundle:StudentAdmin:edit.html.twig")
     */
    public function installAction()
    {
      $em = $this->getDoctrine()->getManager();
      if ($em->getRepository('GessehUserBundle:User')->findAll()) {
        return $this->redirect($this->generateUrl('homepage'));
      }

      $manager = $this->container->get('kdb_parameters.manager');
      $mod_simul = $manager->findParamByName('simul_active');

      $student = new Student();
      $form = $this->createForm(new StudentFirstType($mod_simul->getValue()), $student);
      $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if ( $formHandler->process() ) {
        $this->get('session')->getFlashBag()->add('notice', $student . '" enregistré. Vous pouvez maintenant vous identifier.');

        return $this->redirect($this->generateUrl('fos_user_security_login'));
      }

      return array(
        'student'      => null,
        'student_form' => $form->createView(),
      );
    }

    /**
     * Show other students in the same placement
     *
     * @Route("/user/coworkers/{id}", name="GUser_SListStudents", requirements={"id" = "\d+"})
     * @Template()
     */
    public function listStudentsAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->get('security.token_storage')->getToken()->getUsername();
      $placement = $em->getRepository('GessehCoreBundle:Placement')->getByUsername($user, $id);

      if (!$placement)
          throw $this->createNotFoundException('Unable to find placement entity.');

      $students = $em->getRepository('GessehUserBundle:Student')->getSamePlacement($placement->getPeriod()->getId(), $placement->getDepartment()->getId());

      return array(
          'students'  => $students,
          'placement' => $placement,
      );
    }
}
