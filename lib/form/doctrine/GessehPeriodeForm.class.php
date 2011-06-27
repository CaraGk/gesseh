<?php

/**
 * GessehPeriode form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehPeriodeForm extends BaseGessehPeriodeForm
{
  public function configure()
  {
    $this->widgetSchema['debut']->setOption('format', '%day% - %month% - %year%');
    $this->widgetSchema['fin']->setOption('format', '%day% - %month% - %year%');

    $this->validatorSchema->setPostValidator(
      new sfValidatorSchemaCompare('debut', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'fin',
        array('throw_global_error' => true),
        array('invalid' => 'La date de début des stages ("%left_field%") doit être avant la date de fin des stages ("%right_field%")')
      )
    );

    if (csSettings::get('mod_simul') == null) {
      unset($this['debut_simul'], $this['fin_simul']);
    } else {
      $this->widgetSchema['debut_simul']->setOption('format', '%day% - %month% - %year%');
      $this->widgetSchema['fin_simul']->setOption('format', '%day% - %month% - %year%');

      $this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
        new sfValidatorSchemaCompare('debut', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'fin',
          array('throw_global_error' => true),
          array('invalid' => 'La date de début des stages ("%left_field%") doit être avant la date de fin des stages ("%right_field%")')
        ),
        new sfValidatorSchemaCompare('debut_simul', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'fin_simul',
          array('throw_global_error' => true),
          array('invalid' => 'La date de début des simulations ("%left_field%") doit être avant la date de fin des simulations ("%right_field%")')
        ),
        new sfValidatorSchemaCompare('fin_simul', sfValidatorSchemaCompare::LESS_THAN_EQUAL, 'debut',
          array('throw_global_error' => true),
          array('invalid' => 'La date de fin des simulations ("%left_field%") doit être avant la date de début des stages ("%right_field%")')
        )
      )));
    }
  }
}
