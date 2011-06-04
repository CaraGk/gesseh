<?php

/**
 * GessehCritere filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGessehCritereFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'form'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'add_empty' => true)),
      'titre' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'type'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ratio' => new sfWidgetFormFilterInput(),
      'ordre' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'form'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehFormEval'), 'column' => 'id')),
      'titre' => new sfValidatorPass(array('required' => false)),
      'type'  => new sfValidatorPass(array('required' => false)),
      'ratio' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ordre' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('gesseh_critere_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehCritere';
  }

  public function getFields()
  {
    return array(
      'id'    => 'Number',
      'form'  => 'ForeignKey',
      'titre' => 'Text',
      'type'  => 'Text',
      'ratio' => 'Number',
      'ordre' => 'Number',
    );
  }
}
