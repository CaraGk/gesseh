<?php

/**
 * GessehEtudiant form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GessehEtudiantMainappForm extends BaseGessehEtudiantForm
{
  /* Initialisation du formulaire */
  public function configure()
  {
    unset($this['promo_id'], $this['classement'], $this['utilisateur'], $this['updated_at'], $this['nom'], $this['prenom'], $this['email'], $this['token_mail']);

    $this->embedRelation('sfGuardUser');
    $this->embedForm('MdP', new gessehUserPasswordForm(sfContext::getInstance()->getUser()->getGuardUser()));

/*    $this->validatorSchema['token_mail'] = new sfValidatorAnd(array(
      $this->validatorSchema['token_mail'],
      new sfValidatorEmail(),
    ));

    $this->widgetSchema['email'] = new sfWidgetFormInputHidden();
    $this->widgetSchema->setLabel('token_mail', 'Email');
*/
  }

}
