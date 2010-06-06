<?php

/**
 * Change Password form.
 *
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: $
 */

class UserChangePassForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'cmdp' => new sfWidgetFormInput(),
      'nmdp' => new sfWidgetFormInput(),
      'vnmdp' => new sfWidgetFormInput()
    ));

    $this->setValidators(array(
      'cmdp' => new sfValidatorString(array('required' => false, 'max_length' => '20')),
      'nmdp' => new sfValidatorString(array('required' => false, 'max_length' => '20', 'min_length' => '6')),
      'vnmdp' => new sfValidatorString(array('required' => false, 'max_length' => '20', 'min_length' => '6'))
    ));
      

    $this->widgetSchema->setLabel('cmdp', 'Mot de passe actuel');
    $this->widgetSchema->setLabel('nmdp', 'Nouveau mot de passe');
    $this->widgetSchema->setLabel('vnmdp', 'Confirmer le mot de passe');

    $this->widgetSchema->setNameFormat('mdp[%s]');
  }

  public function save()
  {
    if ($this->getValue('cmdp') != null)
    {
      if(sfContent::getInstance()->getUser()->getGuardUser()->checkPassword($this->getValue('cmdp')))
      {
        if($this->getValue('nmdp') == $this->getValue('vnmdp'))
	{
	  sfContent::getInstance()->getUser()->getGuardUser()->setPassword($this->getValue('nmdp'));
	  sfContent::getInstance()->getUser()->getGuardUser()->save();
	  sfContent::getInstance()->getUser()->setFlash('notice','Mot de passe changé avec succès.');
	}
	else
	{
	  sfContent::getInstance()->getUser()->setFlash('error','Erreur : les 2 mots de passes ne sont pas identiques.');
	}
      }
      else
      {
        sfContent::getInstance()->getUser()->setFlash('error','Erreur : le mot de passe est erroné.');
      }
    }
  }

}

