<?php


class GessehPeriodeTable extends Doctrine_Table
{

    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehPeriode');
    }

    /* Retourne l'id de la période active pour les simulations */
    public function getActivePeriode()
    {
      $q = Doctrine_Query::create()
        ->from('GessehPeriode a')
        ->where('debut_simul <= ?', date('Y-m-d'))
        ->andWhere('fin_simul >= ?', date('Y-m-d'))
        ->limit(1)
        ->fetchOne();

      if ($q)
        return $q->getId();
      else
        return false;
    }

    public function getLastPeriodeId()
    {
      $q = Doctrine_Query::create()
        ->from('GessehPeriode a')
        ->orderBy('fin desc')
        ->limit(1)
        ->fetchOne();

      return $q->getId();
    }
}
