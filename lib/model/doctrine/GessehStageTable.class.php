<?php


class GessehStageTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehStage');
    }

    public function retrieveAdminStageList(Doctrine_Query $q)
    {
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.GessehTerrain c')
        ->leftJoin($rootAlias . '.GessehPeriode d')
	->leftJoin($rootAlias . '.GessehEtudiant e')
	->leftJoin($rootAlias . '.GessehFormEval f')
	->leftJoin('c.GessehHopital g');
      return $q;
    }

    public function getStagesEtudiant($etudiant)
    {
      $q = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftJoin('a.GessehTerrain b')
      ->leftJoin('b.GessehHopital e')
      ->leftJoin('a.GessehPeriode c')
      ->leftJoin('a.GessehEtudiant d')
      ->where('d.id = ?', $etudiant)
      ->OrderBy('a.is_active DESC, c.debut ASC');

      return $q->execute();
    }

    public function getStageUniqueEtudiant($stage_id)
    {
      $q = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftJoin('a.GessehTerrain b')
      ->where('a.id = ?', $stage_id)
      ->limit(1);
      
      return $q->fetchOne();
    }

    public function getActiveStages()
    {
      $q = Doctrine_Query::create()
        ->from('GessehStage a')
	->leftJoin('a.GessehTerrain b')
	->leftJoin('b.GessehHopital e')
	->leftJoin('a.GessehPeriode c')
	->leftJoin('a.GessehEtudiant d')
	->leftJoin('d.GessehPromo f')
	->where('a.is_active = ?', '1')
	->orderBy('d.nom asc, d.prenom asc, c.debut ASC');
      
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
