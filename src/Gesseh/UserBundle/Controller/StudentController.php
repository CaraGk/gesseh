<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2016 Pierre-François Angrand
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
use Gesseh\UserBundle\Form\UserAdminType,
    Gesseh\UserBundle\Form\UserHandler;

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
     * @Template()
     */
    public function installAction()
    {
      $em = $this->getDoctrine()->getManager();
      if ($em->getRepository('GessehUserBundle:User')->findAll()) {
        return $this->redirect($this->generateUrl('homepage'));
      }

      $um = $this->container->get('fos_user.user_manager');
      $request = $this->get('request');
      $user = $um->createUser();
      $form = $this->createForm(new UserAdminType($user));
      $formHandler = new UserHandler($form, $request, $um);

      if ( $formHandler->process() ) {
        $this->get('session')->getFlashBag()->add('notice', 'Administrateur "' . $user->getUsername() . '" enregistré. Vous pouvez maintenant vous identifier.');

        return $this->redirect($this->generateUrl('fos_user_security_login'));
      }

      return array(
        'form' => $form->createView(),
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

      $students = $em->getRepository('GessehUserBundle:Student')->getSamePlacement($placement->getRepartition()->getPeriod()->getId(), $placement->getRepartition()->getDepartment()->getId());

      return array(
          'students'  => $students,
          'placement' => $placement,
      );
    }
}
