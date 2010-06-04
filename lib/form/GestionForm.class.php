<?php

/**
 * Gestion form.
 * 
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-François "Pilou" Angrand
 * @version    SVN: $Id: GestionForm.class.php 20147 2010-06-01 11:46:57Z PierreFrançoisPilouAngrand $
 */

class GestionForm extends sfForm
{
  public function configure()
  {
  }
  
  public function configureForm(Doctrine_Collection $gesseh_promos, array $choices_promo)
  {
    $widgets = array(
      'fichier' => new sfWidgetFormInputFile(array('label' => 'Nouvelle promo PCEM 2')),
      );
    $validators = array(
      'fichier' => new sfValidatorFile(),
      );
   
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

/*  public function getConnection()
  {
    return parent::getConnection();
  }

  protected function doUpdateObject($values)
  {
    return parent::doUpdateObject($values);
  }

  public function processValues($values)
  {
    return parent::processValues($values);
  }
*/

  public function updatePromo()
  {
    $max_promo = Doctrine::getTable('GessehPromo')->count();
    for($i=1 ; $i < $max_promo ; $i++)
      Doctrine::getTable('GessehEtudiant')->changePromo($this->getValue('promo_debut_'.$i), $this->getValue('promo_fin_'.$i));
  }

  public function saveFichier()
  {
//    if (file_exists($this->getObject()->getFile()))
//      unlink($this->getObject()->getFile());
    
    $file = $this->getValue('fichier');
    $filename = sha1($file->getOriginalName()).$file->getExtension($file->getOriginalExtension());
    $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);

    if($file->isSaved())
      Doctrine::getTable('GessehEtudiant')->importPromo(sfConfig::get('sf_upload_dir').'/'.$filename);
  }
}

