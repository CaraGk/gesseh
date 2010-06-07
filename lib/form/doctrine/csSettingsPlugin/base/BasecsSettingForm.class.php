<?php

/**
 * csSetting form base class.
 *
 * @method csSetting getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François 'Pilou' Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasecsSettingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'name'            => new sfWidgetFormInputText(),
      'type'            => new sfWidgetFormInputText(),
      'widget_options'  => new sfWidgetFormTextarea(),
      'value'           => new sfWidgetFormTextarea(),
      'setting_group'   => new sfWidgetFormInputText(),
      'setting_default' => new sfWidgetFormTextarea(),
      'slug'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'            => new sfValidatorString(array('max_length' => 255)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'widget_options'  => new sfValidatorString(array('required' => false)),
      'value'           => new sfValidatorString(array('required' => false)),
      'setting_group'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'setting_default' => new sfValidatorString(array('required' => false)),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorAnd(array(
        new sfValidatorDoctrineUnique(array('model' => 'csSetting', 'column' => array('name'))),
        new sfValidatorDoctrineUnique(array('model' => 'csSetting', 'column' => array('slug'))),
      ))
    );

    $this->widgetSchema->setNameFormat('cs_setting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'csSetting';
  }

}
