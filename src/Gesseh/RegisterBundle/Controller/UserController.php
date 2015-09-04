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
    Gesseh\RegisterBundle\Form\RegisterHandler;

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
     * @Route("/", name="GRegister_UCreate")
     * @Template()
     */
    public function createAction()
    {
        $em = $this->getDoctrine()->getManager();

        $questions = $em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();

        $form = $this->createForm(new RegisterType($questions));
        $form_handler = new RegisterHandler($form, $this->get('request'), $em);
        if($form_handler->process()) {
            $this->get('session')->getFlashBag()->add('notice', 'Utilisateur créé.');

            return $this->redirect($this->generateUrl('GRegister_UValidate'));
        }

        return array(
            'form'      => $form->createView(),
            'questions' => $questions,
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
