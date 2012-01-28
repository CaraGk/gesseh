<?php

require_once dirname(__FILE__).'/../lib/admFormEvalGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/admFormEvalGeneratorHelper.class.php';

/**
 * admFormEval actions.
 *
 * @package    gesseh
 * @subpackage admFormEval
 * @author     Pierre-François 'Pilou' Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admFormEvalActions extends autoAdmFormEvalActions
{
  public function executeListAddCriteria(sfWebRequest $request)
  {
    $this->gesseh_form_eval = $this->getRoute()->getObject();
    $this->gesseh_form_eval->addCriteria();
    $this->form = $this->configuration->getForm($this->gesseh_form_eval);
    $this->getUser()->setFlash('notice', 'Nouveau critère ajouté au formulaire.');
    $this->redirect(array('sf_route' => 'gesseh_form_eval_edit', 'sf_subject' => $this->gesseh_form_eval));
  }
}
