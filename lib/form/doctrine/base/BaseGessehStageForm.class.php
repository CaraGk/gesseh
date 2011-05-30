<?php

/**
 * GessehStage form base class.
 *
 * @method GessehStage getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehStageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'terrain_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehTerrain'), 'add_empty' => false)),
      'periode_id'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPeriode'), 'add_empty' => false)),
      'etudiant_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehEtudiant'), 'add_empty' => false)),
      'form'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'add_empty' => false)),
      'is_active'   => new sfWidgetFormInputCheckbox(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'terrain_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehTerrain'))),
      'periode_id'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPeriode'))),
      'etudiant_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehEtudiant'))),
      'form'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'))),
      'is_active'   => new sfValidatorBoolean(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gesseh_stage[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehStage';
  }

}
