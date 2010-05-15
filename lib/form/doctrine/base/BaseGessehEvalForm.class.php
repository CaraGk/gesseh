<?php

/**
 * GessehEval form base class.
 *
 * @method GessehEval getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehEvalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'stage_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehStage'), 'add_empty' => false)),
      'critere_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'add_empty' => false)),
      'valeur'     => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'stage_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehStage'))),
      'critere_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'))),
      'valeur'     => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gesseh_eval[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehEval';
  }

}
