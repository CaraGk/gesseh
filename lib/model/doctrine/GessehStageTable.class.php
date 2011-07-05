<?php


class GessehStageTable extends Doctrine_Table
{

    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehStage');
    }

    /* Récupère la liste des stages avec les jointures correspondantes */
    public function retrieveAdminStageList(Doctrine_Query $q)
    {
      $rootAlias = $q->getRootAlias();
      $q->leftJoin($rootAlias . '.GessehTerrain c')
        ->leftJoin($rootAlias . '.GessehPeriode d')
        ->leftJoin($rootAlias . '.GessehEtudiant e')
        ->leftJoin($rootAlias . '.GessehFormEval f')
        ->leftJoin('c.GessehHopital g')
        ->leftJoin('e.GessehPromo h')
        ->leftJoin('e.sfGuardUser i')
        ->orderBy('h.ordre asc, i.last_name asc, i.first_name asc, d.id asc');

      return $q;
    }

    /* Récupère la liste des stages d'un étudiant */
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

    /* Récupère les informations concernant un stage */
    public function getStageUniqueEtudiant($stage_id)
    {
      $q = Doctrine_Query::create()
      ->from('GessehStage a')
      ->leftJoin('a.GessehTerrain b')
      ->where('a.id = ?', $stage_id)
      ->limit(1);

      return $q->fetchOne();
    }

    /* Récupère la liste des stages actifs (qui n'ont pas été évalués) */
    public function getActiveStages()
    {
      $q = Doctrine_Query::create()
        ->from('GessehStage a')
        ->leftJoin('a.GessehTerrain b')
        ->leftJoin('b.GessehHopital e')
        ->leftJoin('a.GessehPeriode c')
        ->leftJoin('a.GessehEtudiant d')
        ->leftJoin('d.GessehPromo f')
        ->leftJoin('d.sfGuardUser g')
        ->where('a.is_active = ?', '1')
        ->orderBy('g.last_name asc, g.first_name asc, c.debut ASC');

      return $q->execute();
    }

    /* Change le statut is_active du stage à false */
    // Attention : Collision du module/plugin Eval avec les Stages dans la base >> Tester la présence d'évaluation pour chaque stage serait mieux.
    public function valideActiveStage($stage_id)
    {
      $q = Doctrine_Query::create()
        ->update('GessehStage a')
        ->set('a.is_active', '?', '0')
        ->where('a.id = ?', $stage_id);

      return $q->execute();
    }

    /* Depreciated : Importe une liste de stages d'un fichier MsExcel */
    public static function importFichier($fichier)
    {
      $data = new sfExcelReader($fichier);

      for($i = 1 ; $i <= $data->rowcount($sheet_index=0) ; $i++)
      {
        $stage = new GessehStage();
        $date_debut = explode('/', $data->val($i, csSettings::get('excelrownumber_stage_debut')));
        $date_fin = explode('/', $data->val($i, csSettings::get('excelrownumber_stage_fin')));
        $periode = Doctrine::getTable('GessehPeriode')->find(array('debut' => '20'.$date_debut[2].'/'.$date_debut[1].'/'.$date_debut[0], 'fin' => '20'.$date_fin[2].'/'.$date_fin[1].'/'.$date_fin[0]));
        $stage->setPeriodeId($periode->getId());
        $etudiant = Doctrine::getTable('GessehEtudiant')->find(array('nom' => $data->val($i, csSettings::get('excelrownumber_stage_nom')), 'prenom' => $data->val($i, csSettings::get('excelrownumber_stage_prenom'))));
        $stage->setEtudiantId($etudiant->getId());
        $stage->setForm();
        //	$stage->setPromoId($promo->getId());
        $stage->save();
      }

      return $data->rowcount($sheet_index=0);
    }

}
