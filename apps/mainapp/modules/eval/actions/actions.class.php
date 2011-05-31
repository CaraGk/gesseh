<?php

/**
 * eval actions.
 *
 * @package    gesseh
 * @subpackage eval
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class evalActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
/*    $this->gesseh_evals = Doctrine::getTable('GessehEval')
      ->createQuery('a')
      ->execute();
*/
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getStagesEtudiant($this->getUser()->getUsername());
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getUsername();
    $this->gesseh_evals = Doctrine::getTable('GessehEval')->getEvalsFromStage($request->getParameter('idstage'));
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getUsername();
    $this->gesseh_stage = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request->getParameter('idstage'));
    $this->forward404Unless($this->gesseh_stage);

    $gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($this->gesseh_stage->getForm());
    foreach($gesseh_criteres as $critere)
    {
      $form_tmp = new GessehEvalForm();
      $form_tmp->setEmbdedForm($critere, $this->gesseh_stage->getId());
      if(!isset($this->form))
        $this->form = $form_tmp;
      else
        $this->form->mergeForm($form_tmp);
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getUsername();
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->gesseh_stage = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request->getParameter('idstage'));
    $this->gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($this->gesseh_stage->getForm());
    foreach($this->gesseh_criteres as $critere)
    {
      $form_tmp = new GessehEvalForm();
      $form_tmp->setEmbdedForm($critere, $this->gesseh_stage->getId());
      if(!isset($this->form))
        $this->form = $form_tmp;
      else
	$this->form->mergeForm($form_tmp);
    }

    $this->processForm($request, $this->form, $this->gesseh_criteres);

    $this->setTemplate('new');
  }

  protected function processForm(sfWebRequest $request, sfForm $form, $criteres)
  {
    $this->user = $this->getUser()->getUsername();
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $id = $form->embdedSave($criteres);
      $this->getUser()->setFlash('notice', 'Formulaire d\'évaluation correctement soumis, merci.');
      $this->redirect('eval/show?idstage='.$id);
    }
  }
}
