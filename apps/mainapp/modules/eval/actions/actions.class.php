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
    $this->gesseh_eval = Doctrine::getTable('GessehStage')->getStageUniqueEtudiant($request);
    $this->forward404Unless($this->gesseh_eval);

    $this->gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($this->gesseh_eval->getGessehTerrain()->getFormId(), 'radio(5)');
  }

/*  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehEvalForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GessehEvalForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
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

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gesseh_eval = $form->save();

      $this->redirect('eval/edit?id='.$gesseh_eval->getId());
    }
  }
}
