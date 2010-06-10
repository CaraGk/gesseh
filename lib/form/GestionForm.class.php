<?php

/**
 * Gestion form.
 * 
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: GestionForm.class.php 20147 2010-06-01 11:46:57Z PierreFrançoisPilouAngrand $
 */

class GestionForm extends sfForm
{
  public function configure()
  {
  }
  
  public function configureForm(Doctrine_Collection $gesseh_promos, array $choices_promo)
  {
    $widgets = array();
    $validators = array();
   
    $count = 1;
    foreach($gesseh_promos as $promo)
    {
      $id_next = $promo->getId() + 1;
      if(isset($choices_promo[$id_next]))
      {
        $add_widgets = array(
          'promo_debut_'.$count => new sfWidgetFormChoice(array('choices' => $choices_promo, 'multiple' => false, 'label' => $count.'. Promo de départ', 'default' => $promo->getId())),
	  'promo_fin_'.$count => new sfWidgetFormChoice(array('choices' => $choices_promo, 'multiple' => false, 'label' => 'Promo d\'arrivée', 'default' => $id_next)),
	);
        $widgets = array_merge($widgets, $add_widgets);

        $add_validators = array(
          'promo_debut_'.$count => new sfValidatorDoctrineChoice(array('model' => $this->getModelName('GessehCritere'), 'column' => 'id')),
	  'promo_fin_'.$count => new sfValidatorDoctrineChoice(array('model' => $this->getModelName('GessehCritere'), 'column' => 'id')),
	);
        $validators = array_merge($validators, $add_validators);
      
        $count++;
      }
    }
    
    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setNameFormat('gestion[%s]');
  }

  public function getModelName()
  {
    return 'GessehPromo';
  }

  public function changePromo()
  {
    $max_promo = Doctrine::getTable('GessehPromo')->count();
    for($i=1 ; $i < $max_promo ; $i++)
      Doctrine::getTable('GessehEtudiant')->changePromo($this->getValue('promo_debut_'.$i), $this->getValue('promo_fin_'.$i));
  }
/*
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (is_null($forms))
      $forms = $this->getEmbeddedForms();
    foreach ($forms as $key => $form)
    {
      if ($form instanceof sfFormDoctrine)
      {
        $form->bind($this->values[$key]);
	$form->save($con);
	$form->saveEmbeddedForms($con);
      }
      elseif ($form instanceof sfForm)
      {
        if($form->isMultipart())
          $form->bind($this->values[$key], $this->values[$key]);
	else
	  $form->bind($this->values[$key]);
	if($form->isBound())
          $form->save();
	$form->saveEmbeddedForms($con);
      }
      else
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
    }
  } 
*/
}

