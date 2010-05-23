<?php

/**
 * eval actions.
 *
 * @package    gesseh
 * @subpackage eval
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class evalActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->gesseh_evals = Doctrine::getTable('GessehEval')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->gesseh_stage = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request);
    $this->forward404Unless($this->gesseh_stage);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->gesseh_stage = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request);
    $this->forward404Unless($this->gesseh_stage);

//    $this->form = new GessehEvalForm();
//    $this->form->multiConfigure($this->gesseh_stage->getGessehTerrain()->getFormId(), $this->gesseh_stage->getId());
//    $this->form->setHiddenStage($this->gesseh_stage->getId());
    $gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($this->gesseh_stage->getGessehTerrain()->getFormId());
    foreach($gesseh_criteres as $critere)
    {
      $form_tmp = new GessehEvalForm();
      $form_tmp->setEmbdedForm($critere, $this->gesseh_stage->getId());
//      $form_tmp->setStageHiddenDefault($this->gesseh_stage->getId());
      if(!isset($this->form))
        $this->form = $form_tmp;
      else
        $this->form->mergeForm($form_tmp);
    }
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->gesseh_stage = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request);
    $this->gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($this->gesseh_stage->getGessehTerrain()->getFormId());
//    $subForm = new sfForm();
    foreach($this->gesseh_criteres as $critere)
    {
      $form_tmp = new GessehEvalForm();
      $form_tmp->setEmbdedForm($critere, $this->gesseh_stage->getId());
//      $form_tmp->setStageHiddenDefault($this->gesseh_stage->getId());
      if(!isset($this->form))
        $this->form = $form_tmp;
      else
	$this->form->mergeForm($form_tmp);
//      $subForm->embedForm($critere->getId(), $form_tmp);
    }
//    $this->form = ...

//    $this->form = new GessehEvalForm();
//    $this->form->multiConfigure($request->getParameter('form_id'), $request->getParameter('stage_id'));

    $this->processForm($request, $this->form, $this->gesseh_criteres);

    $this->setTemplate('new');
  }

/*  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_eval = Doctrine::getTable('GessehEval')->find(array($request->getParameter('id'))), sprintf('Object gesseh_eval does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEvalForm($gesseh_eval);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_eval = Doctrine::getTable('GessehEval')->find(array($request->getParameter('id'))), sprintf('Object gesseh_eval does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEvalForm($gesseh_eval);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_eval = Doctrine::getTable('GessehEval')->find(array($request->getParameter('id'))), sprintf('Object gesseh_eval does not exist (%s).', $request->getParameter('id')));
    $gesseh_eval->delete();

    $this->redirect('eval/index'); 
  } */

  protected function processForm(sfWebRequest $request, sfForm $form, $criteres)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $id = $form->embdedSave($criteres);
      $this->redirect('eval/show?id='.$id);
    }
  }
}
