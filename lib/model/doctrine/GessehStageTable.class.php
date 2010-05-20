<?php


class GessehStageTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehStage');
    }

    public function getStagesEtudiant($etudiant)
    {
      $q = $this->gesseh_stages = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftjoin('a.GessehTerrain b')
      ->leftjoin('b.GessehHopital e')
      ->leftjoin('a.GessehPeriode c')
      ->leftjoin('a.GessehEtudiant d')
      ->where('d.id = ?', $etudiant->getParameter('id'))
      ->addOrderBy('a.is_active DESC, c.debut ASC');

      return $q->execute();
    }

    public function getStageUniqueEtudiant($request)
    {
      $q = $this->gesseh_eval = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftjoin('a.GessehTerrain b')
      ->where('a.id = ?', $request->getParameter('id'))
      ->limit(1);
      
      return $q->fetchOne();
    }
}
