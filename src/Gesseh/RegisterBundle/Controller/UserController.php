<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
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
     * @Route("/create", name="GRegister_URegister")
     * @Template()
     */
    public function registerAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->container->get('fos_user.user_manager');
        $pm = $this->container->get('kdb_parameters.manager');
        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $token = $tokenGenerator->generateToken();
        $url = $this->generateUrl('fos_user_registration_confirm', array('token' => $token), true);

        $form = $this->createForm(new RegisterType($pm->findParamByName('simul_active')));
        $form_handler = new RegisterHandler($form, $this->get('request'), $em, $um, $pm->findParamByName('reg_payment'), $token);

        if($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Utilisateur ' . $username . ' créé.');
            $email = \Swift_Message::newInstance()
                ->setSubject('GESSEH - Confirmation d\'adresse mail')
                ->setFrom($this->container->getParameter('mailer_mail'))
                ->setTo($username)
                ->setBody($this->renderView('GessehRegisterBundle:User:confirmation.html.twig', array('email' => $username, 'url' => $url)), 'text/html')
                ->addPart($this->renderView('GessehRegisterBundle:User:confirmation.txt.twig', array('email' => $username, 'url' => $url)), 'text/plain')
            ;
            $this->get('mailer')->send($email);

            return $this->redirect($this->generateUrl('GRegister_UValidate'));
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
        $user = $this->get('request')->query->get('user');

        $questions = $em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $membership = $em->getRepository('Gesseh\RegisterBundle\Entity\Membership')->getLastByUsername($user);

        $form = $this->createForm(new QuestionType($questions));
        $form_handler = new QuestionHandler($form, $this->get('request'), $em, $membership, $questions);
        if($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Utilisateur créé.');

            return $this->redirect($this->generateUrl('GRegister_UValidate', array('user' => $user)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Validate
     *
     * @Route("/validate", name="GRegister_UValidate")
     * @Template()
     */
    public function validateAction()
    {
        $user = $this->get('request')->query->get('user');

        return array(
            'email' => $user,
        );
    }

    /**
     * Re-send confirmation email
     *
     * @Route("/send", name="GRegister_USendConfirmation")
     */
    public function sendConfirmationAction()
    {
        $username = $this->get('request')->query->get('username');
        $um = $this->container->get('fos_user.user_manager');

        return $this->redirect($this->generateUrl('GRegister_UValidate', array('user' => $username)));
    }
}
