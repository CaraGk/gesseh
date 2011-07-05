<?php


class GessehTerrainTable extends Doctrine_Table
{

    /* Magic Method : Récupère la liste des terrains de stage */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehTerrain');
    }

    /* Récupère la liste complète des terrains de stage, ordonnée par tri pour un pager */
    public function getListeTerrains($tri = 'b.titre asc')
    {
//      $tri = $this->checkOrderTri($order);

      $q = Doctrine_Query::create()
        ->from('GessehTerrain a')
        ->leftJoin('a.GessehHopital b')
        ->leftJoin('a.GessehFiliere c')
        ->orderBy($tri);

      return $q;
    }

    /* Récupère les informations d'un terrain de stage */
    public function getTerrainUnique($id)
    {
      $q = Doctrine_Query::create()
        ->from('GessehTerrain a')
        ->leftjoin('a.GessehHopital b')
        ->where('id = ?', $id)
        ->limit(1);

      return $q->fetchOne();
    }

    /* Récupère les terrains sous forme d'un tableau */
    public function getActiveTerrainTbl()
    {
      $q = Doctrine_Query::create()
        ->from('GessehTerrain a')
        ->where('is_active = ?', true);

      $terrains = $q->execute();

      foreach ($terrains as $terrain)
        $poste[$terrain->getId()] = $terrain->getTotal();

      return $poste;
    }

    private function checkOrderTri($request = null)
    {
    // Y a-t'il un/des tris dans la requête ? / Mise en forme
/*
     if ($request->getParameter('tri1'))
        $tri = $this->makeProperTri($request->getParameter('tri1')) . ' ' . $this->makeProperOrder($request->getParameter('order1'));
      else
        $tri = 'b.nom asc, a.filiere asc';

      if ($request->getParameter('tri2'))
        $tri .= ', ' . $this->makeProperTri($request->getParameter('tri2')) . ' ' . $this->makeProperOrder($request->getParameter('order2'));

      return $tri;
*/
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
