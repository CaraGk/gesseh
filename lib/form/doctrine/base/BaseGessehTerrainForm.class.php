<?php

/**
 * GessehTerrain form base class.
 *
 * @method GessehTerrain getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'hopital_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => false)),
      'titre'               => new sfWidgetFormInputText(),
      'filiere'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFiliere'), 'add_empty' => false)),
      'patron'              => new sfWidgetFormInputText(),
      'localisation'        => new sfWidgetFormInputText(),
      'gardes_lieu'         => new sfWidgetFormInputText(),
      'gardes_horaires'     => new sfWidgetFormInputText(),
      'astreintes_horaires' => new sfWidgetFormInputText(),
      'total'               => new sfWidgetFormInputText(),
      'page'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPage'), 'add_empty' => true)),
      'is_active'           => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'hopital_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'))),
      'titre'               => new sfValidatorString(array('max_length' => 100)),
      'filiere'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFiliere'))),
      'patron'              => new sfValidatorString(array('max_length' => 50)),
      'localisation'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'gardes_lieu'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'gardes_horaires'     => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'astreintes_horaires' => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'total'               => new sfValidatorInteger(),
      'page'                => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPage'), 'required' => false)),
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
