<?php

namespace Gesseh\SimulationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\SimulationBundle\Entity\Wish;
use Gesseh\SimulationBundle\Form\WishType;
use Gesseh\SimulationBundle\Form\WishHandler;

/**
 * @Route("/u/s")
 */
class SimulationController extends Controller
{
    /**
     * @Route("/", name="GSimulation_SIndex")
     * @Template()
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getEntityManager();
      $user = $this->get('security.context')->getToken()->getUsername();
      $student = $em->getRepository('GessehUserBundle:Student')->getByUsername($user);
      $wishes = $em->getRepository('GessehSimulationBundle:Wish')->getByStudent($student->getId());

      $new_wish = new Wish();
      $form = $this->createForm(new WishType(), $new_wish);
      $formHandler = new WishHandler($form, $this->get('request'), $em, $student);

      if($formHandler->process()) {
        $this->get('session')->setFlash('notice', 'Nouveau vœu : "' . $new_wish->getDepartment() . '" enregistré.');
        return $this->redirect($this->generateUrl('GSimulation_SIndex'));
      }

      return array(
        'wishes'    => $wishes,
        'wish_form' => $form->createView(),
      );
    }
}
