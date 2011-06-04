<?php

/**
 * GessehPromo filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGessehPromoFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'titre'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'ordre'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'active' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'form'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'titre'  => new sfValidatorPass(array('required' => false)),
      'ordre'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'active' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'form'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehFormEval'), 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('gesseh_promo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehPromo';
  }

  public function getFields()
  {
    return array(
      'id'     => 'Number',
      'titre'  => 'Text',
      'ordre'  => 'Number',
      'active' => 'Boolean',
      'form'   => 'ForeignKey',
    );
  }
}
