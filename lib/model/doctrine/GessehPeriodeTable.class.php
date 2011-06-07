<?php


class GessehPeriodeTable extends Doctrine_Table
{

    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehPeriode');
    }

    public function getActivePeriode()
    {
      $q = Doctrine_Query::create()
        ->from('GessehPeriode a')
        ->where('debut <= ?', date('Y-m-d'))
        ->andWhere('fin >= ?', date('Y-m-d'))
//        ->andWhere('type = ?', 'simul');  // selectionne le type de période correspondant aux simulations de choix
        ->limit(1)
        ->fetchOne();

      if ($q)
        return $q->getId();
      else
        return false;
    }
}
