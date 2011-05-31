<?php

/**
 * GessehTerrain filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hopital_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => true)),
      'titre'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'filiere'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFiliere'), 'add_empty' => true)),
      'patron'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'localisation'        => new sfWidgetFormFilterInput(),
      'gardes_lieu'         => new sfWidgetFormFilterInput(),
      'gardes_horaires'     => new sfWidgetFormFilterInput(),
      'astreintes_horaires' => new sfWidgetFormFilterInput(),
      'total'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'page'                => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPage'), 'add_empty' => true)),
      'is_active'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'hopital_id'          => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehHopital'), 'column' => 'id')),
      'titre'               => new sfValidatorPass(array('required' => false)),
      'filiere'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehFiliere'), 'column' => 'id')),
      'patron'              => new sfValidatorPass(array('required' => false)),
      'localisation'        => new sfValidatorPass(array('required' => false)),
      'gardes_lieu'         => new sfValidatorPass(array('required' => false)),
      'gardes_horaires'     => new sfValidatorPass(array('required' => false)),
      'astreintes_horaires' => new sfValidatorPass(array('required' => false)),
      'total'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'page'                => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehPage'), 'column' => 'id')),
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
      'titre'               => 'Text',
      'filiere'             => 'ForeignKey',
      'patron'              => 'Text',
      'localisation'        => 'Text',
      'gardes_lieu'         => 'Text',
      'gardes_horaires'     => 'Text',
      'astreintes_horaires' => 'Text',
      'total'               => 'Number',
      'page'                => 'ForeignKey',
      'is_active'           => 'Boolean',
    );
  }
}
