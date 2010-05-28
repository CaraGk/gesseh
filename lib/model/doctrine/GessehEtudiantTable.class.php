<?php


class GessehEtudiantTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEtudiant');
    }

    public static function updateEtudiantPromo($promo_depart, $promo_arrivee)
    {
      $q = Doctrine::create()
        ->from('GessehEtudiant a')
	->where('promo_id = ?', $promo_depart);
    }
}
