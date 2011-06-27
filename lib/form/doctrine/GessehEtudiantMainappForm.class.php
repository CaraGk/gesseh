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
    unset($this['promo_id'], $this['classement'], $this['utilisateur'], $this['updated_at']);

    $years = range(1970, 2000);
    $this->widgetSchema['naissance']->setOption('format', '%day% - %month% - %year%');
    $this->widgetSchema['naissance']->setOption('years', array_combine($years, $years));

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
