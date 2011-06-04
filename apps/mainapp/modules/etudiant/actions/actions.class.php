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
    $this->user = $this->getUser()->getUsername();
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array('id' => $this->user)), sprintf('Utilisateur inconnu : (%s).', $this->getUser()->getUsername()));

//    if(!$gesseh_etudiant->getTokenMail())
//      $gesseh_etudiant->setTokenMail($gesseh_etudiant->getEmail());

    $this->form = new GessehEtudiantMainappForm($gesseh_etudiant);
  }

  /* Valide les paramètres utilisateur */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($this->getUser()->getUsername())), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEtudiantMainappForm($gesseh_etudiant);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }


  /* Validation de l'adresse email par un token */
/*  public function executeToken(sfWebRequest $request)
  {
    if(Doctrine::getTable('GessehEtudiant')->validTokenMail($request['userid'], $request['token']))
      $this->getUser()->setFlash('notice','Adresse e-mail validée.');
    else
      $this->getUser()->setFlash('error','Erreur de validation d\'adresse e-mail.');

    if($this->getUser()->isAuthenticated())
      $this->redirect('@eval_index');
    else
      $this->redirect('@homepage');
  }
*/

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
