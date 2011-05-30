<?php

/**
 * GessehTerrain filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hopital_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => true)),
      'filiere'             => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'patron'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'localisation'        => new sfWidgetFormFilterInput(),
      'gardes_lieu'         => new sfWidgetFormFilterInput(),
      'gardes_horaires'     => new sfWidgetFormFilterInput(),
      'astreintes_horaires' => new sfWidgetFormFilterInput(),
      'is_active'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'hopital_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehHopital'), 'column' => 'id')),
      'filiere'             => new sfValidatorPass(array('required' => false)),
      'patron'              => new sfValidatorPass(array('required' => false)),
      'localisation'        => new sfValidatorPass(array('required' => false)),
      'gardes_lieu'         => new sfValidatorPass(array('required' => false)),
      'gardes_horaires'     => new sfValidatorPass(array('required' => false)),
      'astreintes_horaires' => new sfValidatorPass(array('required' => false)),
      'is_active'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('gesseh_terrain_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehTerrain';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'hopital_id'          => 'ForeignKey',
      'filiere'             => 'Text',
      'patron'              => 'Text',
      'localisation'        => 'Text',
      'gardes_lieu'         => 'Text',
      'gardes_horaires'     => 'Text',
      'astreintes_horaires' => 'Text',
      'is_active'           => 'Boolean',
    );
  }
}
