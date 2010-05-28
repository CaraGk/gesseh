<?php

/**
 * gestion actions.
 *
 * @package    gesseh
 * @subpackage gestion
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class gestionActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getActiveStages();
  }

  public function executeMail(sfWebRequest $request)
  {
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getActiveStages();
    foreach($this->gesseh_stages as $stage)
    {
      $message = $this->getMailer()->compose(
        array('tmp@angrand.fr' => 'Administration Paris-Ouest'),
	$stage->getGessehEtudiant()->getEmail(),
	'[Paris-Ouest] Rappel : évaluation à rendre',
	<<<EOF
	{$stage->getGessehEtudiant()->getPrenom()},

L'évaluation en ligne du stage : {$stage->getGessehTerrain()->getFiliere()} à {$stage->getGessehTerrain()->getGessehHopital()->getNom()} du {$stage->getGessehPeriode()->getDebut()} au {$stage->getGessehPeriode()->getFin()} n'a pas été remplie.

Nous vous rappelons que l'évaluation des stages est obligatoire et nous vous invitons à le faire dans les plus brefs délais en cliquant sur le lien suivant :

http://test.angrand.fr/sandbox/web/index.php/etudiant

L'administration de la faculté de médecine Paris-Ile-de-France-Ouest.

Ce message a été généré automatiquement, merci de ne pas y répondre.
EOF
      );
      $this->getMailer()->send($message);
      $this->count++;
    }

    $this->getUser()->setFlash('notice', sprintf('Vous venez d\'envoyer %s mails de rappel.', $this->count));
    $this->redirect('gestion/index');
  }

  public function executeNew(sfWebRequest $request)
  {
//    $this->gesseh_criteres = Doctrine::getTable('GessehCritere');
    $this->form = new gestionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GestionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($request->getParameter('id'))), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEtudiantForm($gesseh_etudiant);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($request->getParameter('id'))), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehEtudiantForm($gesseh_etudiant);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($request->getParameter('id'))), sprintf('Object gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
    $gesseh_etudiant->delete();

    $this->redirect('gestion/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gesseh_etudiant = $form->save();

      $this->redirect('gestion/edit?id='.$gesseh_etudiant->getId());
    }
  }
}
