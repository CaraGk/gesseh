<?php

/**
 * CopisimSimulation filter form base class.
 *
 * @package    gesseh
 * @subpackage filter
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCopisimSimulationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'etudiant' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehEtudiant'), 'add_empty' => true)),
      'poste'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('GessehTerrain'), 'add_empty' => true)),
      'reste'    => new sfWidgetFormFilterInput(),
      'absent'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'etudiant' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehEtudiant'), 'column' => 'id')),
      'poste'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('GessehTerrain'), 'column' => 'id')),
      'reste'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'absent'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('copisim_simulation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CopisimSimulation';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'etudiant' => 'ForeignKey',
      'poste'    => 'ForeignKey',
      'reste'    => 'Number',
      'absent'   => 'Boolean',
    );
  }
}
