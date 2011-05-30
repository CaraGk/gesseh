<?php

/**
 * GessehTerrain form base class.
 *
 * @method GessehTerrain getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'hopital_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => false)),
      'filiere'             => new sfWidgetFormInputText(),
      'patron'              => new sfWidgetFormInputText(),
      'localisation'        => new sfWidgetFormInputText(),
      'gardes_lieu'         => new sfWidgetFormInputText(),
      'gardes_horaires'     => new sfWidgetFormInputText(),
      'astreintes_horaires' => new sfWidgetFormInputText(),
      'is_active'           => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'hopital_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'))),
      'filiere'             => new sfValidatorString(array('max_length' => 100)),
      'patron'              => new sfValidatorString(array('max_length' => 50)),
      'localisation'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'gardes_lieu'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'gardes_horaires'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'astreintes_horaires' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'is_active'           => new sfValidatorBoolean(array('required' => false)),
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
