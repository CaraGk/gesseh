<?php

/**
 * GessehEtudiant form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehEtudiantForm extends BaseGessehEtudiantForm
{
  public function configure()
  {
    unset($this['utilisateur'], $this['anonyme'], $this['created_at'], $this['updated_at']);

    if (csSettings::get('mod_simul') == false)
      unset($this['classement']);

    $years = range(1970, 2000);
    $this->widgetSchema['naissance']->setOption('format', '%day% - %month% - %year%');
    $this->widgetSchema['naissance']->setOption('years', array_combine($years, $years));

    $this->embedRelation('sfGuardUser');
  }

}
