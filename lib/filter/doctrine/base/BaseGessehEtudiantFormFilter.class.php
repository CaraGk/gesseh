<?php

/**
 * GessehEtudiant filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGessehEtudiantFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'nom'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'prenom'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'promo_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehPromo'), 'add_empty' => true)),
      'email'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'token_mail' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tel'        => new sfWidgetFormFilterInput(),
      'naissance'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'nom'        => new sfValidatorPass(array('required' => false)),
      'prenom'     => new sfValidatorPass(array('required' => false)),
      'promo_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehPromo'), 'column' => 'id')),
      'email'      => new sfValidatorPass(array('required' => false)),
      'token_mail' => new sfValidatorPass(array('required' => false)),
      'tel'        => new sfValidatorPass(array('required' => false)),
      'naissance'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('gesseh_etudiant_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GessehEtudiant';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'nom'        => 'Text',
      'prenom'     => 'Text',
      'promo_id'   => 'ForeignKey',
      'email'      => 'Text',
      'token_mail' => 'Text',
      'tel'        => 'Text',
      'naissance'  => 'Date',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
