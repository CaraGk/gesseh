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
use Gesseh\UserBundle\Form\StudentUserType;
use Gesseh\UserBundle\Form\StudentHandler;

/**
 * Student controller.
 *
 * @Route("/user")
 */
class StudentController extends Controller
{
    /**
     * @Route("/edit", name="GUser_SEdit")
     * @Template()
     */
    public function editAction()
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->get('security.context')->getToken()->getUsername();
      $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($user);

      if( !$student )
        throw $this->createNotFoundException('Unable to find Student entity.');

      $form = $this->createForm(new StudentUserType(), $student);
      $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if( $formHandler->process() ) {
        $this->get('session')->getFlashBag()->add('notice', 'Votre compte a bien été modifié.');
        return $this->redirect($this->generateUrl('GUser_SEdit'));
      }

      return array(
        'form' => $form->createView(),
      );
    }
}
