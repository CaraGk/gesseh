<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public static function changePromo($promo_depart, $promo_arrivee)
    {
      $q = Doctrine_Query::create()
        ->update('GessehEtudiant a')
	->set('promo_id', '?', $promo_arrivee)
	->where('promo_id = ?', $promo_depart);
      return $q->execute();
    }

    public static function importPromo($fichier)
    {
    }
}
