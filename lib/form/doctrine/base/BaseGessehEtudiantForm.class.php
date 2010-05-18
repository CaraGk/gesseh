<?php

/**
 * GessehEtudiant form base class.
 *
 * @method GessehEtudiant getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehEtudiantForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'nom'        => new sfWidgetFormInputText(),
      'prenom'     => new sfWidgetFormInputText(),
      'promo_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'), 'add_empty' => false)),
      'email'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'nom'        => new sfValidatorString(array('max_length' => 255)),
      'prenom'     => new sfValidatorString(array('max_length' => 255)),
      'promo_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'))),
      'email'      => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('gesseh_etudiant[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehEtudiant';
  }

}
