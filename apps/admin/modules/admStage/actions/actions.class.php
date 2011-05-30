<?php

require_once dirname(__FILE__).'/../lib/admStageGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/admStageGeneratorHelper.class.php';

/**
 * admStage actions.
 *
 * @package    gesseh
 * @subpackage admStage
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admStageActions extends autoAdmStageActions
{
  public function executeListImportStages()
  {
    $this->form = new ImportForm();
    $this->setTemplate('import');
  }
  
  public function executeImportcreate()
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new ImportForm();
    
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save('GessehStage');
      $this->redirect('admStage/index');
    }
    
    $this->setTemplate('import');
  }

  public function executeListMailRappel()
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
    $this->redirect('admStage/index');
  }
}
