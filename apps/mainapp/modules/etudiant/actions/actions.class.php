<?php

/**
 * etudiant actions.
 *
 * @package    gesseh
 * @subpackage etudiant
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class etudiantActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->user = $this->getUser()->getUsername();
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getStagesEtudiant($this->user);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehStageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GessehStageForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_stage = Doctrine::getTable('GessehStage')->find(array($request->getParameter('id'))), sprintf('Object gesseh_stage does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehStageForm($gesseh_stage);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_stage = Doctrine::getTable('GessehStage')->find(array($request->getParameter('id'))), sprintf('Object gesseh_stage does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehStageForm($gesseh_stage);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_stage = Doctrine::getTable('GessehStage')->find(array($request->getParameter('id'))), sprintf('Object gesseh_stage does not exist (%s).', $request->getParameter('id')));
    $gesseh_stage->delete();

    $this->redirect('etudiant/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gesseh_stage = $form->save();

      $this->redirect('etudiant/edit?id='.$gesseh_stage->getId());
    }
  }
}
