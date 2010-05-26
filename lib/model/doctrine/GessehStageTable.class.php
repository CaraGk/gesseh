<?php


class GessehStageTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehStage');
    }

    public function getStagesEtudiant($etudiant)
    {
      $q = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftjoin('a.GessehTerrain b')
      ->leftjoin('b.GessehHopital e')
      ->leftjoin('a.GessehPeriode c')
      ->leftjoin('a.GessehEtudiant d')
      ->where('d.id = ?', $etudiant)
      ->OrderBy('a.is_active DESC, c.debut ASC');

      return $q->execute();
    }

    public function getStageUniqueEtudiant($stage_id)
    {
      $q = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftjoin('a.GessehTerrain b')
      ->where('a.id = ?', $stage_id)
      ->limit(1);
      
      return $q->fetchOne();
    }

    public function getActiveStages()
    {
      $q = Doctrine_Query::create()
        ->from('GessehStage a')
	->leftjoin('a.GessehTerrain b')
	->leftjoin('b.GessehHopital e')
	->leftjoin('a.GessehPeriode c')
	->leftjoin('a.GessehEtudiant d')
	->leftjoin('d.GessehPromo f')
	->where('a.is_active = ?', '1')
	->OrderBy('d.nom asc, d.prenom asc, c.debut ASC');
      
      return $q->execute();
    }

    public function valideActiveStage($stage_id)
    {
      $q = Doctrine_Query::create()
        ->update('GessehStage a')
        ->set('a.is_active', '?', '0')
	->where('a.id = ?', $stage_id);

      return $q->execute();
    }
}
