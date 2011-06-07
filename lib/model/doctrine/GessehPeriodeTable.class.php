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
        ->where('debut <= ?', 'now()')
        ->andWhere('fin >= ?', 'now()')
//        ->andWhere('type = ?', 'simul');  // selectionne le type de période correspondant aux simulations de choix
        ->limit(1)
        ->fetchOne();

      if ($q)
        return $q->getId();
      else
        return false;
    }
}
