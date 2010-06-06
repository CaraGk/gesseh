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
    $this->embedForm('MdP', new UserChangePassForm());

//    $this->useFields(array('nom', 'prenom', 'email', 'cmdp', 'nmdp', 'vnmdp'));
    unset($this['promo_id'], $this['created_at'], $this['updated_at']);

    $this->validatorSchema['token_mail'] = new sfValidatorAnd(array(
      $this->validatorSchema['token_mail'],
      new sfValidatorEmail(),
    ));

    $this->widgetSchema['email'] = new sfWidgetFormInputHidden();
    $this->widgetSchema->setLabel('token_mail', 'Email');
  }

}
