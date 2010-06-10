<?php

/**
 * gestion actions.
 *
 * @package    gesseh
 * @subpackage gestion
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class evaluationActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getActiveStages();
  }

  public function executeMail(sfWebRequest $request)
  {
    $this->gesseh_stages = Doctrine::getTable('GessehStage')->getActiveStages();
    $lien = csSettings::get('BaseURL');
    foreach($this->gesseh_stages as $stage)
    {
      $message = $this->getMailer()->compose(
        array(csSettings::get('email') => csSettings::get('email_nom')),
	$stage->getGessehEtudiant()->getEmail(),
	'['.csSettings::get('email_prefixe').'] Rappel : évaluations à rendre',
	<<<EOF
	{$stage->getGessehEtudiant()->getPrenom()},

L'évaluation en ligne du stage : {$stage->getGessehTerrain()->getFiliere()} à {$stage->getGessehTerrain()->getGessehHopital()->getNom()} du {$stage->getGessehPeriode()->getDebut()} au {$stage->getGessehPeriode()->getFin()} n'a pas été remplie.

Nous vous rappelons que l'évaluation des stages est obligatoire et nous vous invitons à le faire dans les plus brefs délais en cliquant sur le lien suivant :

{$lien}etudiant

L'administration.

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
    $this->form = new GessehCritereForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $this->form = new GessehCritereForm();
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', sprintf('Ajout du nouveau formulaire %s effectuée.', $this->form->getValue('titre')));
      $this->redirect('evaluation/index');
    }

    $this->setTemplate('new');
  }

  public function executeComments(sfWebRequest $request)
  {
    $this->gesseh_evals = Doctrine::getTable('GessehEval')->getAllComments();
  }
  
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_eval = Doctrine::getTable('GessehEval')->find(array($request->getParameter('id'))), sprintf('Object eval don\'t exist (%s).', $request->getParameter('id')));
    $gesseh_eval->delete();

    $this->redirect('gestion/comments');
  }

}
