<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\RegisterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI,
    JMS\SecurityExtraBundle\Annotation as Security;
use Gesseh\RegisterBundle\Entity\Partner,
    Gesseh\RegisterBundle\Form\PartnerType,
    Gesseh\RegisterBundle\Form\PartnerHandler;

/**
 * RegisterBundle PartnerController
 *
 * @Route("/")
 */
class PartnerController extends Controller
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
     * @Route("/partners", name="GRegister_PaIndex")
     * @Security\Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function indexAction()
    {
        $partners = $this->em->getRepository('GessehRegisterBundle:Partner')->findAll();

        return array(
            'partners' => $partners,
        );
    }

    /**
     * @Route("/partner/{id}", name="GRegister_PaEdit", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction(Request $request, Partner $partner = null)
    {
        $questions = $this->em->getRepository('GessehRegisterBundle:MemberQuestion')->findAll();
        $form = $this->createForm(new PartnerType(), $partner, array('questions' => $questions));
        $form_handler = new PartnerHandler($form, $request, $this->em, $this->um);

        if ($form_handler->process()) {
            $this->session->getFlashBag()->add('notice', 'Partenaire ' . $partner . ' enregistré.');
            return $this->redirect($this->generateUrl('GRegister_PaIndex'));
        }

        return array(
            'form'    => $form->createView(),
            'partner' => $partner,
        );

    }

    /**
     * @Route("/partner/{id}/delete", name="GRegister_PaDelete", requirements={"id" = "\d+"})
     * @Security\Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction(Partner $partner)
    {
        $this->em->remove($partner);
        $this->em->flush();
        $this->session->getFlashBag()->add('notice', 'Partenaire ' . $partner . ' supprimé.');
        return $this->redirect($this->generateUrl('GRegister_PaIndex'));
    }

}
