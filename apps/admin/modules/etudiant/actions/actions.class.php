<?php

/**
 * gestion actions.
 *
 * @package    gesseh
 * @subpackage admin etudiant
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class etudiantActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->gesseh_etudiants = Doctrine::getTable('GessehEtudiant')
      ->createQuery('a')
      ->orderBy('a.promo_id asc')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehEtudiantForm();
  }
  
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new GessehEtudiantForm();
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', sprintf('Ajout des étudiants effectuée.'));
      $this->redirect('etudiant/index');
    }
    
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.id desc')
      ->execute();
    $this->count_promos = $this->gesseh_promos->count();
    $this->form = new GestionForm();
    $this->form->configureForm($this->gesseh_promos, Doctrine::getTable('GessehPromo')->getChoices());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.id desc')
      ->execute();
    $this->count_promos = $this->gesseh_promos->count();
    $this->form = new GestionForm();
    $this->form->configureForm($this->gesseh_promos, Doctrine::getTable('GessehPromo')->getChoices());

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())
    {
      $this->form->changePromo();
      $this->getUser()->setFlash('notice', sprintf('Passage à la promotion supérieure pour tous les étudiants.'));
      $this->redirect('etudiant/import');
    }
    
    $this->setTemplate('new');
  }

  public function executeImport(sfWebRequest $request)
  {
    $this->form = new ImportForm();
  }

  public function executeImportCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new ImportForm();

    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->save('GessehEtudiant');
      $this->redirect('admEtudiant/index');
    }

    $this->setTemplate('import');
  }

}
