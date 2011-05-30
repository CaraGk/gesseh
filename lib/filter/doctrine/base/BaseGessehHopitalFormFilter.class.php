<?php

/**
 * GessehHopital filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehHopitalFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nom'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'adresse'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'telephone' => new sfWidgetFormFilterInput(),
      'web'       => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'nom'       => new sfValidatorPass(array('required' => false)),
      'adresse'   => new sfValidatorPass(array('required' => false)),
      'telephone' => new sfValidatorPass(array('required' => false)),
      'web'       => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gesseh_hopital_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehHopital';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'nom'       => 'Text',
      'adresse'   => 'Text',
      'telephone' => 'Text',
      'web'       => 'Text',
    );
  }
}
