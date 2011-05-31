<?php

/**
 * GessehCritere form base class.
 *
 * @method GessehCritere getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehCritereForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'    => new sfWidgetFormInputHidden(),
      'form'  => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'), 'add_empty' => false)),
      'titre' => new sfWidgetFormInputText(),
      'type'  => new sfWidgetFormInputText(),
      'ratio' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'    => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'form'  => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehFormEval'))),
      'titre' => new sfValidatorString(array('max_length' => 100)),
      'type'  => new sfValidatorString(array('max_length' => 10)),
      'ratio' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gesseh_critere[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehCritere';
  }

}
