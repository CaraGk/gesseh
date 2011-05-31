<?php

/**
 * GessehReferent filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseGessehReferentFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'utilisateur' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => true)),
      'promo'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'), 'add_empty' => true)),
      'tel'         => new sfWidgetFormFilterInput(),
      'divers'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'utilisateur' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('sfGuardUser'), 'column' => 'id')),
      'promo'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehPromo'), 'column' => 'id')),
      'tel'         => new sfValidatorPass(array('required' => false)),
      'divers'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gesseh_referent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehReferent';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'utilisateur' => 'ForeignKey',
      'promo'       => 'ForeignKey',
      'tel'         => 'Text',
      'divers'      => 'Text',
    );
  }
}
