<?php

/**
 * GessehEval form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehEvalForm extends BaseGessehEvalForm
{
  public function configure()
  {
//    $this->useFields(array('stage_id', 'critere_id', 'valeur'));
    unset($this['created_at'], $this['updated_at']);
  }
  
  public function multiConfigure($form_type, $stage_id)
  {
    $gesseh_criteres = Doctrine::getTable('GessehCritere')->getCriteres($form_type);
    
    $widgets = array(
      'id'         => new sfWidgetFormInputHidden(),
      'stage_id'   => new sfWidgetFormInputHidden(array('default' => $stage_id)),
      'created_at' => new sfWidgetFormDateTime(),            
      'updated_at' => new sfWidgetFormDateTime(),
      'form_id'    => new sfWidgetFormInputHidden(array('default' => $form_type)),
    );
    
    $validators = array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'stage_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehStage'))),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    );
    
    foreach($gesseh_criteres as $critere)
    {
      $add_widgets = array('critere_'.$critere->getId() => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'add_empty' => false)), 'valeur_'.$critere->getId() => new sfWidgetFormInputText(), );
      array_merge($widgets, $add_widgets);

      $add_validators = array('critere'.$critere->getId() => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'))), 'valeur'.$critere->getId() => new sfValidatorString(array('max_length' => 255)), );
      array_merge($validators, $add_validators);
    }
    
    $this->setWidgets($widgets);
    
    $this->widgetSchema->setNameFormat('gesseh_eval[%s]');

    unset($this['created_at'], $this['updated_at']);

    foreach($gesseh_criteres as $critere)
    {
      $this->widgetSchema['critere'.$critere->getId()] = new sfWidgetFormInputHidden(array('default' => $critere->getId()));
      if($critere->getType() == 'radio')
      {
        $choices = array();
	for($i = 0; $i < $critere->getRatio(); $i++)
	  array_push($choices, $i);
	$this->widgetSchema['valeur'.$critere->getId()] = new sfWidgetFormSelectRadio(array('choices' => $choices, 'label' => $critere->getTitre()));
      }
      elseif($critere->getType() == 'text')
      {
        $this->widgetSchema['valeur'.$critere->getId()] = new sfWidgetFormTextarea(array('label' => $critere->getTitre()));
      }
    }
  }

  public function setStageHiddenDefault($stage_id)
  {
    $this->widgetSchema['stage_id']->setDefault($stage_id);
    $this->widgetSchema['stage_id']->setHidden(true);
  }

  // Redéfinition du formulaire avec stage_id fixe et critere_id/valeur indexés par GessehCritere->getId()
  public function setEmbdedForm($critere, $stage_id)
  {
    // Requête de sélection d'un unique stage_id
    $query_stageid = Doctrine::getTable('GessehStage')->createQuery('a')->where('a.id = ?', $stage_id);

    // Redéfinition des widgets du formulaire
    $widgets = array(
      'id'         => new sfWidgetFormInputHidden(),
      'stage_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehStage'), 'query' => $query_stageid)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
      'critere_id_'.$critere->getId() => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'add_empty' => false)),
      'valeur_'.$critere->getId() => new sfWidgetFormInputText(),
    );
    $this->setWidgets($widgets);
    
    // Redéfinition des validateurs associés
    $validators = array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'stage_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehStage'))),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
      'critere_id_'.$critere->getId() => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'))),
      'valeur_'.$critere->getId() => new sfValidatorString(array('max_length' => 255)),
    );
    $this->setValidators($validators);

    // Attribution des 'name' modifiés du formulaire
    $this->widgetSchema->setNameFormat('gesseh_eval[%s]');

    // Suppression des champs created_at et update_at du formulaire
    unset($this['created_at'], $this['updated_at']);

    // critere_id est un champ invisible dont la valeur est GessehCritere->getId()
    $this->widgetSchema['critere_id_'.$critere->getId()] = new sfWidgetFormInputHidden(array('default' => $critere->getId()));

    // valeur est un champ radio ou un textarea en fonction de GessehCritere->getType()
    if($critere->getType() == 'radio')
    {
      // Définition des choix possibles pour un champ radio en fonction de GessehCritere->getRatio()
      $choices = array();
      for($i = 0; $i < $critere->getRatio(); $i++)
        array_push($choices, $i);

      $this->widgetSchema['valeur_'.$critere->getId()] = new sfWidgetFormSelectRadio(array('choices' => $choices, 'label' => $critere->getTitre()));
    }
    elseif($critere->getType() == 'text')
    {
      $this->widgetSchema['valeur_'.$critere->getId()] = new sfWidgetFormTextarea(array('label' => $critere->getTitre()));
    }
  }

  public function embdedSave($criteres)
  {
    $this->disableCSRFProtection();
    foreach($criteres as $critere)
    {
      $form = new GessehEvalForm();
      $taintedValues = array(
        'stage_id' => $this->getValue('stage_id'),
	'critere_id' => $this->getValue('critere_id_'.$critere->getId()),
	'valeur' => $this->getValue('valeur_'.$critere->getId()),
      );
      $form->bind($taintedValues);
      $form->save();
      $active = Doctrine::getTable('GessehStage')->valideActiveStage($this->getValue('stage_id'));
    }
    return $form->getValue('stage_id');
  }
}
