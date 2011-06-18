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
    unset($this['utilisateur'], $this['anonyme'], $this['created_at'], $this['updated_at'], $this['token_mail'], $this['nom'], $this['prenom'], $this['email']);

    $this->embedRelation('sfGuardUser');
  }

}
