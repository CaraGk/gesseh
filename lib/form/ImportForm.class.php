<?php

/**
 * Import form.
 * 
 * @package    gesseh
 * @subpackage form
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: GestionForm.class.php 20147 2010-06-01 11:46:57Z PierreFrançoisPilouAngrand $
 */

class ImportForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'fichier' => new sfWidgetFormInputFile(),
      ));
    $this->setValidators(array(
      'fichier' => new sfValidatorFile(),
      ));
   
    $this->widgetSchema->setNameFormat('import[%s]');
  }

  public function save($table = 'GessehEtudiant')
  {
//    if (file_exists($this->getObject()->getFile()))
//      unlink($this->getObject()->getFile());
print_r($this->getValues()).print_r($this->getTaintedValues()).print_r($this->isBound());
    $file = $this->getValue('fichier');
    echo $file;
    $filename = sha1($file->getOriginalName()).$file->getExtension($file->getOriginalExtension());
    $file->save(sfConfig::get('sf_upload_dir').'/'.$filename);

    if($file->isSaved())
      Doctrine::getTable($table)->importFichier(sfConfig::get('sf_upload_dir').'/'.$filename);
  }
}

