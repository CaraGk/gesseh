<?php

/**
 * GessehHopital form base class.
 *
 * @method GessehHopital getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehHopitalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'nom'        => new sfWidgetFormInputText(),
      'adresse'    => new sfWidgetFormInputText(),
      'telephone'  => new sfWidgetFormInputText(),
      'web'        => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'nom'        => new sfValidatorString(array('max_length' => 255)),
      'adresse'    => new sfValidatorString(array('max_length' => 255)),
      'telephone'  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'web'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'GessehHopital', 'column' => array('nom')))
    );

    $this->widgetSchema->setNameFormat('gesseh_hopital[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehHopital';
  }

}
