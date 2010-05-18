<?php

/**
 * GessehTerrain filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehTerrainFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'hopital_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehHopital'), 'add_empty' => true)),
      'filiere'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'patron'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'localisation' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'form_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehCritere'), 'add_empty' => true)),
      'is_active'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'hopital_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehHopital'), 'column' => 'id')),
      'filiere'      => new sfValidatorPass(array('required' => false)),
      'patron'       => new sfValidatorPass(array('required' => false)),
      'localisation' => new sfValidatorPass(array('required' => false)),
      'form_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehCritere'), 'column' => 'id')),
      'is_active'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
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
      'id'           => 'Number',
      'hopital_id'   => 'ForeignKey',
      'filiere'      => 'Text',
      'patron'       => 'Text',
      'localisation' => 'Text',
      'form_id'      => 'ForeignKey',
      'is_active'    => 'Boolean',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
