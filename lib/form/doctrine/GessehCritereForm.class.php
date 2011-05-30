<?php

/**
 * GessehCritere form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehCritereForm extends BaseGessehCritereForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at']);

    $this->widgetSchema['type'] = new sfWidgetFormSelect(array(
      'choices' => array(
        'radio' => 'radio',
        'text' => 'text'
      ),
      'multiple' => false
    ));

    $this->validatorSchema['type'] = new sfValidatorAnd(array(
      $this->validatorSchema['type'],
      new sfValidatorChoice(array(
        'choices' => array(
	  'radio' => 'radio',
	  'text' => 'text'
	),
	'multiple' => false,
	'min' => 1
    )))); 


  }
}
