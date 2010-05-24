<?php


class GessehTerrainTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehTerrain');
    }

    public function getListeTerrains($tri = false, $order = false)
    {
      $q = $this->gesseh_terrains = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b');

      if(!isset($order))
        $order = 'asc';

      if($tri == 'hopital')
        $q->OrderBy('b.nom '.$order);
      elseif($tri == 'terrain')
        $q->OrderBy('a.filiere '.$order);
      else
        $q->OrderBy('b.nom asc, a.filiere asc');
      
      return $q->execute();
    }

    public function getTerrainUnique($request)
    {
      $q = $this->gesseh_terrain = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b')
      ->where('id = ?', $request->getParameter('id'))
      ->limit(1);

      return $q->fetchOne();
    }

}
