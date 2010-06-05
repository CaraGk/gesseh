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

}

