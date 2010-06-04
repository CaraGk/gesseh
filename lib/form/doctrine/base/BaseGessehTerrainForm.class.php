<?php

/**
 * GessehTerrain form base class.
 *
 * @method GessehTerrain getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François 'Pilou' Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'hopital_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => false)),
      'filiere'      => new sfWidgetFormInputText(),
      'patron'       => new sfWidgetFormInputText(),
      'localisation' => new sfWidgetFormInputText(),
      'form_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'add_empty' => false)),
      'is_active'    => new sfWidgetFormInputCheckbox(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'hopital_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'))),
      'filiere'      => new sfValidatorString(array('max_length' => 255)),
      'patron'       => new sfValidatorString(array('max_length' => 255)),
      'localisation' => new sfValidatorString(array('max_length' => 255)),
      'form_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'required' => false)),
      'is_active'    => new sfValidatorBoolean(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gesseh_terrain[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehTerrain';
  }

}
