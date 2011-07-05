<?php

/**
 * GessehHopital form base class.
 *
 * @method GessehHopital getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehHopitalForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'titre'     => new sfWidgetFormInputText(),
      'adresse'   => new sfWidgetFormInputText(),
      'telephone' => new sfWidgetFormInputText(),
      'web'       => new sfWidgetFormInputText(),
      'page'      => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'titre'     => new sfValidatorString(array('max_length' => 100)),
      'adresse'   => new sfValidatorString(array('max_length' => 255)),
      'telephone' => new sfValidatorString(array('max_length' => 14, 'required' => false)),
      'web'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'page'      => new sfValidatorString(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'GessehHopital', 'column' => array('titre')))
    );

    $this->widgetSchema->setNameFormat('gesseh_hopital[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehHopital';
  }

}
