<?php

namespace Gesseh\ParameterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\ParameterBundle\Entity\Parameter;
use Gesseh\ParameterBundle\Form\ParameterType;
use Gesseh\ParameterBundle\Form\ParameterHandler;

/**
 * ParameterAdmin controller.
 *
 * @Route("/admin/param")
 */
class AdminController extends Controller
{
  /**
   * @Route("/", name="GParameter_PAIndex")
   * @Template()
   */
  public function indexAction()
  {
    $manager = $this->container->get('kdb_parameters.manager');
    $parameters = $manager->findParams();

    return array(
      'parameters'     => $parameters,
      'parameter_id'   => null,
      'parameter_form' => null,
    );
  }

  /**
   * @Route("/{id}/e", name="GParameter_PAEditParameter", requirements={"id" = "\d+"})
   * @Template("GessehParameterBundle:Admin:index.html.twig")
   */
  public function editParameterAction($id)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $parameters = $em->getRepository('GessehParameterBundle:Parameter')->findAll();

    $parameter = $em->getRepository('GessehParameterBundle:Parameter')->find($id);

    if( !$parameter )
      throw $this->createNotFoundException('Unable to find Parameter entity.');

    $form = $this->createForm(new ParameterType(), $parameter);
    $formHandler = new ParameterHandler($form, $this->get('request'), $em);

    if( $formHandler->process() ) {
      $this->get('session')->setFlash('notice', 'Paramètre "' . $parameter->getLabel() . ' : ' . $parameter->getValue() . '" modifié.');
      return $this->redirect($this->generateUrl('GParameter_PAIndex'));
    }

    return array(
      'parameters'     => $parameters,
      'parameter_id'   => $id,
      'parameter_form' => $form->createView(),
    );
  }
}
