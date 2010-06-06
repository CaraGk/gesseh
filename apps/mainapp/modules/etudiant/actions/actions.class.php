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

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array('id' => $this->getUser()->getUsername())), sprintf('Utilisateur inconnu : (%s).', $this->getUser()->getUsername()));
    if(!$gesseh_etudiant->getTokenMail())
      $gesseh_etudiant->setTokenMail($gesseh_etudiant->getEmail());
    $this->form = new GessehEtudiantForm($gesseh_etudiant);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($this->getUser()->getUsername())), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEtudiantForm($gesseh_etudiant);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeMail(sfWebRequest $request)
  {
    if (isset($request['token']))
      Doctrine::getTable('GessehEtudiant')->validTokenMail($request['iduser'], $request['token']);
    
    if (Doctrine::getTable('GessehEtudiant')->checkValidMail($this->getUser()->getUsername()))
    {
      $this->redirect('etudiant/index');
    }
    else
    {
      $this->getUser()->setFlash('error','Adresse email non fournie ou non validée !');
      $this->redirect('etudiant/edit');
    }
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->save();
      $form_pass = $form->getEmbeddedForm('MdP');
      $form_pass->bind($form->getValue('MdP'));
      $form_pass->save();
//      $this->getUser()->setFlash('notice','Mise à jour du profil effectuée.');

      $this->redirect('etudiant/edit');
    }
  }
}
