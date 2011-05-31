<?php

/**
 * GessehPromo form base class.
 *
 * @method GessehPromo getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehPromoForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'titre' => new sfWidgetFormInputText(),
      'ordre' => new sfWidgetFormInputText(),
      'form'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'titre' => new sfValidatorString(array('max_length' => 100)),
      'ordre' => new sfValidatorInteger(),
      'form'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gesseh_promo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehPromo';
  }

}
