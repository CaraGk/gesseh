<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2015-2016 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\RegisterBundle\Entity\MemberQuestion,
    Gesseh\RegisterBundle\Form\RegisterType,
    Gesseh\RegisterBundle\Form\RegisterHandler,
    Gesseh\RegisterBundle\Form\JoinType,
    Gesseh\RegisterBundle\Form\JoinHandler,
    Gesseh\RegisterBundle\Form\QuestionType,
    Gesseh\RegisterBundle\Form\QuestionHandler;

/**
 * RegisterBundle RegisterController
 *
 * @Route("/")
 */
class RegisterController extends Controller
{
    /** @DI\Inject */
    private $session;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("fos_user.user_manager") */
    private $um;

    /** @DI\Inject("kdb_parameters.manager") */
    private $pm;

    /**
     * Create Membership
     *
     * @Route("/register/", name="GRegister_URegister")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            $this->session->getFlashBag()->add('error', 'Utilisateur déjà enregistré');
            return $this->redirect($this->generateUrl('GRegister_UJoin'));
        }

        $tokenGenerator = $this->container->get('fos_user.util.token_generator');
        $token = $tokenGenerator->generateToken();

        $form = $this->createForm(new RegisterType($this->pm->findParamByName('simul_active')->getValue()));
        $form_handler = new RegisterHandler($form, $request, $this->em, $this->um, $token);

        if($username = $form_handler->process()) {
            $this->session->getFlashBag()->add('notice', 'Utilisateur ' . $username . ' créé.');

            return $this->redirect($this->generateUrl('GRegister_USendConfirmation', array('email' => $username)));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Send confirmation email
     *
     * @Route("/register/send/{email}", name="GRegister_USendConfirmation", requirements={"email" = ".+\@.+\.\w+" })
     * @Template()
     */
    public function sendConfirmationAction($email)
    {
        $user = $this->um->findUserByUsername($email);

        if(!$user)
            throw $this->createNotFoundException('Aucun utilisateur lié à cette adresse mail.');

        if(!$user->getConfirmationToken())
            throw $this->createNotFoundException('Cet utilisateur n\'a pas de jeton de confirmation défini. Est-il déjà validé ? Contactez un administrateur.');

        $url = $this->generateUrl('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);
        $sendmail = \Swift_Message::newInstance()
                ->setSubject('GESSEH - Confirmation d\'adresse mail')
                ->setFrom($this->container->getParameter('mailer_mail'))
                ->setTo($user->getEmailCanonical())
                ->setBody($this->renderView('GessehRegisterBundle:User:confirmation.html.twig', array('user' => $user, 'url' => $url)), 'text/html')
                ->addPart($this->renderView('GessehRegisterBundle:User:confirmation.txt.twig', array('user' => $user, 'url' => $url)), 'text/plain')
        ;
        $this->get('mailer')->send($sendmail);

        return array(
            'email' => $user->getEmailCanonical(),
        );
    }

    /**
     * Join action
     *
     * @Route("/user/join", name="GRegister_UJoin")
     * @Template()
     * @Security\Secure(roles="ROLE_STUDENT, ROLE_ADMIN")
     */
    public function joinAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED'))
            return $this->redirect($this->generateUrl('GRegister_URegister'));

        $user = $this->getUser();
        $userid = $request->query->get('userid');
        $student = $this->testAdminTakeOver($user, $userid);

        if (null !== $this->em->getRepository('GessehRegisterBundle:Membership')->getCurrentForStudent($student)) {
            $this->session->getFlashBag()->add('error', 'Adhésion déjà à jour de cotisation.');

            if ($userid and $user->hasRole('ROLE_ADMIN'))
                return $this->redirect($this->generateUrl('GRegister_UIndex', array("userid" => $userid)));
            else
                return $this->redirect($this->generateUrl('GRegister_UIndex'));
        }

        $params = array(
            'payment'     => $this->pm->findParamByName('reg_payment')->getValue(),
            'date'        => $this->pm->findParamByName('reg_date')->getValue(),
            'periodicity' => $this->pm->findParamByName('reg_periodicity')->getValue(),
        );
        $form = $this->createForm(new JoinType());
        $form_handler = new JoinHandler($form, $request, $this->em, $student, $params);

        if($membership = $form_handler->process()) {
            $this->session->getFlashBag()->add('notice', 'Adhésion enregistrée pour ' . $student . '.');

            return $this->redirect($this->generateUrl('GRegister_PPrepare', array('gateway' => $membership->getMethod(), 'memberid' => $membership->getId())));
        }

        return array(
            'form' => $form->createView(),
        );

    }

    /**
     * Index action
     *
     * @Route("/user/membership", name="GRegister_UIndex")
     * @Template()
     * @Security\Secure(roles="ROLE_STUDENT, ROLE_ADMIN")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $userid = $request->query->get('userid');
        $student = $this->testAdminTakeOver($user, $userid);

        if ($userid == null && $current_membership = $this->em->getRepository('GessehRegisterBundle:Membership')->getCurrentForStudent($student)) {
            $count_infos = $this->em->getRepository('GessehRegisterBundle:MemberInfo')->countByMembership($student, $current_membership);
            $count_questions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->countAll();
            if ($count_infos < $count_questions) {
                return $this->redirect($this->generateUrl('GRegister_UQuestion'));
            }
        }

        $memberships = $this->em->getRepository('GessehRegisterBundle:Membership')->findBy(array('student' => $student));

        return array(
            'memberships' => $memberships,
            'userid'      => $userid,
            'student'     => $student,
        );
    }

    /**
     * Complementary questions
     *
     * @Route("/user/questions", name="GRegister_UQuestion")
     * @Template()
     * @Security\Secure(roles="ROLE_STUDENT")
     */
    public function questionAction(Request $request)
    {
        $username = $this->get('security.token_storage')->getToken()->getUsername();
        $student = $this->em->getRepository('GessehUserBundle:Student')->getByUsername($username);

        $questions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $membership = $this->em->getRepository('Gesseh\RegisterBundle\Entity\Membership')->getCurrentForStudent($student);

        $form = $this->createForm(new QuestionType($questions));
        $form_handler = new QuestionHandler($form, $request, $this->em, $membership, $questions);
        if($form_handler->process()) {
            $this->session->getFlashBag()->add('notice', 'Informations complémentaires enregistrées.');

            return $this->redirect($this->generateUrl('GRegister_UIndex'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Show MemberInfo action
     *
     * @Route("/user/{memberid}/infos/", name="GRegister_UInfos", requirements={"memberid" = "\d+"})
     * @Template()
     * @Security\Secure(roles="ROLE_STUDENT, ROLE_ADMIN")
     */
    public function showInfosAction(Membership $membership, Request $request)
    {
        $user = $this->getUser();
        $userid = $request->query->get('userid');
        $student = $this->testAdminTakeOver($user, $userid);

        if (!$membership) {
            $this->session->getFlashBag()->add('error', 'Adhésion inconnue.');
            return $this->redirect($this->generateUrl('GRegister_UIndex'));
        }

        $memberinfos = $this->em->getRepository('GessehRegisterBundle:MemberInfo')->getByMembership($student, $membership);

        return array(
            'infos'   => $memberinfos,
            'userid'  => $userid,
            'student' => $student,
        );
    }

    /**
     * Test for admin take over function
     *
     * @return
     */
    public function testAdminTakeOver($user, $user_id = null)
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
