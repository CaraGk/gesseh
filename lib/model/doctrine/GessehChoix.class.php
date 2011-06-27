<?php

/**
 * GessehChoix
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    gesseh
 * @subpackage model
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class GessehChoix extends BaseGessehChoix
{
  /* Enregistre le nouveau voeu de l'étudiant */
  public function save(Doctrine_Connection $conn = null)
  {
    if(null === $this->getEtudiant())
      $this->setEtudiant(sfContext::getInstance()->getUser()->getEtudiantId());

    // Attention : Prendre en compte les choix précédemment supprimés (if similarObject: updateSimilarObject)

    if(null === $this->getOrdre()){
      $this->setOrdre('1');
      Doctrine::getTable('GessehChoix')->cascadeEtudiantChoixDown($this->getEtudiant());
    }

    return parent::save($conn);
  }
}