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
  /* Edite les paramètres utilisateur */
  public function executeEdit(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array('id' => $userid)), sprintf('Utilisateur inconnu : (%s).', $userid));

//    if(!$gesseh_etudiant->getTokenMail())
//      $gesseh_etudiant->setTokenMail($gesseh_etudiant->getEmail());

    $this->form = new GessehEtudiantMainappForm($gesseh_etudiant);
  }

  /* Valide les paramètres utilisateur */
  public function executeUpdate(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($userid)), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEtudiantMainappForm($gesseh_etudiant);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  /* Enregistre les paramètres utilisateur */
  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->save();
      $this->redirect('@etudiant_edit');
    }
  }
}
