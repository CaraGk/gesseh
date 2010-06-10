<?php

/**
 * gestion actions.
 *
 * @package    gesseh
 * @subpackage gestion
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class stageActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->gesseh_stages = Doctrine::getTable('GessehStage');
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehStageForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new GessehStageForm();
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', sprintf('Ajout du nouveau formulaire %s effectuée.', $this->form->getValue('titre')));
      $this->redirect('evaluation/index');
    }
    
    $this->setTemplate('new');
  }

  public function executePeriode(sfWebRequest $request)
  {
    $this->form = new GessehPeriodeForm();
    $this->form->embedForm('Periode2', new GessehPeriodeForm());
    $this->form->embedForm('Periode3', new GessehPeriodeForm());
    $this->form->embedForm('Periode4', new GessehPeriodeForm());
  }

  public function executePeriodecreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GessehPeriodeForm();
    $this->form->embedForm('Periode2', new GessehPeriodeForm());
    $this->form->embedForm('Periode3', new GessehPeriodeForm());
    $this->form->embedForm('Periode4', new GessehPeriodeForm());

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', sprintf('Ajout des nouvelles périodes de stage effectué.'));
      $this->redirect('stage/import');
    }
      
    $this->setTemplate('periode');
  }

  public function executeImport(sfWebRequest $request)
  {
    $this->form = new ImportForm();
  }

  public function executeImportcreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new ImportForm();

    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->save('GessehStage');
      $this->redirect('admStage/index');
    }

    $this->setTemplate('import');
  }

}
