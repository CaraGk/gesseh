<?php

/**
 * GessehChoix form base class.
 *
 * @method GessehChoix getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehChoixForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'etudiant'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehEtudiant'), 'add_empty' => false)),
      'poste'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehTerrain'), 'add_empty' => false)),
      'ordre'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'etudiant'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehEtudiant'))),
      'poste'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehTerrain'))),
      'ordre'      => new sfValidatorInteger(),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gesseh_choix[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehChoix';
  }

}
