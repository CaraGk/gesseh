<?php


class GessehCritereTable extends Doctrine_Table
{

    /* Récupère tous les critères d'évaluation */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehCritere');
    }

    /* Récupère les critères d'évaluation par formulaire */
    public function getCriteres($formulaire)
    {
      $q = Doctrine::getTable('GessehCritere')
      ->createQuery('a')
      ->where('form = ?', $formulaire)
      ->orderBy('ordre asc');

      return $q->execute();
    }

}

?>
