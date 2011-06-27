<?php

/**
 * GessehFormEval form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François 'Pilou' Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehFormEvalForm extends BaseGessehFormEvalForm
{
  public function configure()
  {
    $this->embedRelation('GessehCritere');
    $nouveau = new GessehCritereForm();
    $this->embedForm('Nouvel item', $nouveau);
  }
}
