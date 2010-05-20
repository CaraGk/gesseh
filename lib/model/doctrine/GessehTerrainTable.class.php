<?php


class GessehTerrainTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehTerrain');
    }

    public function getListeTerrains()
    {
      $q = $this->gesseh_terrains = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b')
      ->addOrderBy('b.nom, a.filiere ASC');
      
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
