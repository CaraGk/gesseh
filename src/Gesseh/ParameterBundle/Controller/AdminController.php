<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\ParameterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gesseh\ParameterBundle\Entity\Parameter;
use Gesseh\ParameterBundle\Form\ParameterType;
use Gesseh\ParameterBundle\Form\ParameterHandler;
use Gesseh\ParameterBundle\Form\ParametersType;
use Gesseh\ParameterBundle\Form\ParametersHandler;

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
        $pm = $this->container->get('kdb_parameters.manager');
        $parameters = $pm->findParams();

        $form = $this->createForm(new ParametersType($parameters), $parameters);
        $formHandler = new ParametersHandler($form, $this->get('request'), $pm, $parameters);

        if( $formHandler->process() ) {
            $this->get('session')->getFlashBag()->add('notice', 'Paramètres mis à jour.');
            return $this->redirect($this->generateUrl('GParameter_PAIndex'));
        }

        return array(
            'parameter_form' => $form->createView(),
    );
  }

}
