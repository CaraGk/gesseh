<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Gesseh\UserBundle\Entity\Student,
    Gesseh\UserBundle\Form\StudentUserType,
    Gesseh\UserBundle\Form\StudentHandler,
    Gesseh\UserBundle\Form\UserAdminType,
    Gesseh\UserBundle\Form\UserHandler;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;

/**
 * Student controller.
 *
 * @Route("/")
 */
class StudentController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

    /**
     * @Route("/user/", name="GUser_SShow")
     * @Template()
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();
        $userid = $request->query->get('userid');
        $student = $this->testAdminTakeOver($user, $userid);
        $placements = $this->em->getRepository('GessehCoreBundle:Placement')->getByStudent($student->getId());

        if (true == $this->pm->findParamByName('eval_active')->getValue()) {
            $evaluated = $this->em->getRepository('GessehEvaluationBundle:Evaluation')->getEvaluatedList('array', $student->getUser()->getUsername());
        } else {
            $evaluated = array();
        }

        if (true == $this->pm->findParamByName('reg_active')->getValue()) {
            if ($userid == null && $current_membership = $this->em->getRepository('GessehRegisterBundle:Membership')->getCurrentForStudent($student)) {
                $count_infos = $this->em->getRepository('GessehRegisterBundle:MemberInfo')->countByMembership($student, $current_membership);
                $count_questions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->countAll();
                if ($count_infos < $count_questions) {
                    return $this->redirect($this->generateUrl('GRegister_UQuestion'));
                }
            }
            $memberships = $this->em->getRepository('GessehRegisterBundle:Membership')->findBy(array('student' => $student));
        } else {
            $memberships = array();
        }

        return array(
            'student'     => $student,
            'userid'      => $userid,
            'placements'  => $placements,
            'evaluated'   => $evaluated,
            'memberships' => $memberships,
        );
    }

    /**
     * @Route("/user/edit", name="GUser_SEdit")
     * @Template()
     */
    public function editAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->get('security.token_storage')->getToken()->getUsername();
      $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($user);
      $redirect = $request->query->get('redirect', 'GUser_SEdit');

      if( !$student )
        throw $this->createNotFoundException('Unable to find Student entity.');

      $form = $this->createForm(new StudentUserType(), $student);
      $formHandler = new StudentHandler($form, $this->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if ( $formHandler->process() ) {
        $this->get('session')->getFlashBag()->add('notice', 'Votre compte a bien été modifié.');

        return $this->redirect($this->generateUrl($redirect));
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
    /**
     * Test for admin take over function
     *
     * @return
     */
    private function testAdminTakeOver($user, $user_id = null)
    {
        if ($user->hasRole('ROLE_ADMIN') and $user_id != null) {
            $user = $this->um->findUserBy(array(
                'id' => $user_id,
            ));
        }

        $student = $this->em->getRepository('GessehUserBundle:Student')->getByUsername($user->getUsername());

        if (!$student) {
            $this->session->getFlashBag()->add('error', 'Étudiant inconnu.');
            return $this->redirect($this->generateUrl('GRegister_UIndex'));
        } else {
            return $student;
        }
    }
}
