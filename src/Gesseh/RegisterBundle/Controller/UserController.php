<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2015 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\RegisterBundle\Entity\MemberQuestion,
    Gesseh\RegisterBundle\Form\RegisterType,
    Gesseh\RegisterBundle\Form\RegisterHandler,
    Gesseh\RegisterBundle\Form\QuestionType,
    Gesseh\RegisterBundle\Form\QuestionHandler;

/**
 * RegisterBundle UserController
 *
 * @Route("/register")
 */
class UserController extends Controller
{
    /**
     * Create Membership
     *
     * @Route("/create", name="GRegister_UCreate")
     * @Template()
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $pm = $this->container->get('kdb_parameters.manager');

        $form = $this->createForm(new RegisterType($pm->findParamByName('simul_active')));
        $form_handler = new RegisterHandler($form, $this->get('request'), $em, $um, $pm->findParamByName('reg_payment'));
        if($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Utilisateur créé.');

            return $this->redirect($this->generateUrl('GRegister_UQuestion'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Complementary questions
     *
     * @Route("/questions", name="GRegister_UQuestion")
     * @Template()
     */
    public function questionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $pm = $this->container->get('kdb_parameters.manager');
        $user = $this->get('security.context')->getToken()->getUsername();

        $questions = $em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $membership = $em->getRepository('Gesseh\RegisterBundle\Entity\Membership')->getLastByUsername()

        $form = $this->createForm(new QuestionType($questions));
        $form_handler = new QuestionHandler($form, $this->get('request'), $em, $membership, $questions));
        if($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Utilisateur créé.');

            return $this->redirect($this->generateUrl('GRegister_UValidate'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Validate
     *
     * @Route("/Validate", name="GRegister_UValidate")
     * @Template()
     */
    public function validateAction()
    {
        return array();
    }
}
