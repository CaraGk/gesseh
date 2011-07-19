<?php

/**
 * GessehEtudiant form base class.
 *
 * @method GessehEtudiant getObject() Returns the current form's model object
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseGessehEtudiantForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'promo_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'), 'add_empty' => false)),
      'annee_promo' => new sfWidgetFormInputText(),
      'classement'  => new sfWidgetFormInputText(),
      'tel'         => new sfWidgetFormInputText(),
      'naissance'   => new sfWidgetFormDate(),
      'anonyme'     => new sfWidgetFormInputCheckbox(),
      'utilisateur' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'), 'add_empty' => false)),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'promo_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'))),
      'annee_promo' => new sfValidatorPass(array('required' => false)),
      'classement'  => new sfValidatorInteger(),
      'tel'         => new sfValidatorString(array('max_length' => 14, 'required' => false)),
      'naissance'   => new sfValidatorDate(array('required' => false)),
      'anonyme'     => new sfValidatorBoolean(array('required' => false)),
      'utilisateur' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('sfGuardUser'))),
      'updated_at'  => new sfValidatorDateTime(),
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
