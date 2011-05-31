<?php

/**
 * GessehReferent form base class.
 *
 * @method GessehReferent getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehReferentForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'utilisateur' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'promo'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'), 'add_empty' => false)),
      'tel'         => new sfWidgetFormInputText(),
      'divers'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'utilisateur' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'promo'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'))),
      'tel'         => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'divers'      => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gesseh_referent[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehReferent';
  }

}
