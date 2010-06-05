<?php


class GessehTerrainTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehTerrain');
    }

    public function getListeTerrains($request = null)
    {
    // Récupération de la liste complète des Terrains, ordonnée par tri

      $tri = $this->checkOrderTri($request);

      $q = $this->gesseh_terrains = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b')
      ->orderBy($tri);

      return $q->execute();
    }

    public function getTerrainUnique($id)
    {
    // Récupération d'un seul objet Terrain

      $q = $this->gesseh_terrain = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b')
      ->where('id = ?', $id)
      ->limit(1);

      return $q->fetchOne();
    }

    private function checkOrderTri($request = null)
    {
    // Y a-t'il un/des tris dans la requête ? / Mise en forme

      if ($request->getParameter('tri1'))
        $tri = $this->makeProperTri($request->getParameter('tri1')) . ' ' . $this->makeProperOrder($request->getParameter('order1'));
      else
        $tri = 'b.nom asc, a.filiere asc';

      if ($request->getParameter('tri2'))
        $tri .= ', ' . $this->makeProperTri($request->getParameter('tri2')) . ' ' . $this->makeProperOrder($request->getParameter('order2'));

      return $tri;
    }

    private function makeProperTri($tri)
    {
    // Transformation de l'URL tri en colonne de la table

      if ($tri == 'hopital')
        return 'b.nom';
      elseif ($tri == 'terrain')
        return 'a.filiere';
      else
        return 'b.nom';
    }

    private function makeProperOrder($order)
    {
    // Vérification de l'exactitude de la valeur de l'URL order

      if ($order == 'asc' or $order == 'desc')
        return $order;
      else
        return 'asc';
    }

}
