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
    $this->gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.id desc')
      ->execute();
    $this->count_promos = $this->gesseh_promos->count();
    $this->form = new GestionForm();
    $this->form->configureForm($this->gesseh_promos, Doctrine::getTable('GessehPromo')->getChoices());
    $this->form->embedForm('PromoP2', new ImportForm());
    $this->form->embedForm('Periode1', new GessehPeriodeForm());
    $this->form->embedForm('Periode2', new GessehPeriodeForm());
    $this->form->embedForm('Periode3', new GessehPeriodeForm());
    $this->form->embedForm('Periode4', new GessehPeriodeForm());
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.id desc')
      ->execute();
    $this->count_promos = $this->gesseh_promos->count();
    $this->form = new GestionForm();
    $this->form->configureForm($this->gesseh_promos, Doctrine::getTable('GessehPromo')->getChoices());
    $this->form->embedForm('PromoP2', new ImportForm());
    $this->form->embedForm('Periode1', new GessehPeriodeForm());
    $this->form->embedForm('Periode2', new GessehPeriodeForm());
    $this->form->embedForm('Periode3', new GessehPeriodeForm());
    $this->form->embedForm('Periode4', new GessehPeriodeForm());

    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $form->changePromo();
      $form->saveEmbeddedForms();
      
//      $this->redirect('admEtudiant/index');
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
      $form->save('GessehStage');
      $this->redirect('admStage/index');
    }

    $this->setTemplate('import');
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
