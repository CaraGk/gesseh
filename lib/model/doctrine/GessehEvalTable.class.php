<?php


class GessehEvalTable extends Doctrine_Table
{

    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEval');
    }

    /* Récupère les évaluations pour un stage */
    public function getEvalsFromStage($stage_id)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEval a')
        ->leftjoin('a.GessehStage b')
        ->leftjoin('b.GessehPeriode c')
        ->leftjoin('a.GessehCritere d')
        ->where('a.stage_id = ?', $stage_id)
        ->OrderBy('a.critere_id ASC');

      return $q->execute();
    }

    /* Récupère les évaluations numériques d'un terrain de stage */
    private function getEvalsRadio($terrain)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEval a')
        ->leftjoin('a.GessehStage b')  // Attention : lors du nettoyage de la base (suppressions des vieux enregistrements d'étudiants et de stage) la jointure est perdue !
        ->leftjoin('b.GessehPeriode c')
        ->leftjoin('a.GessehCritere d')
        ->where('b.terrain_id = ?', $terrain)
        ->andWhere('d.type = ?', 'radio')
        ->OrderBy('b.form asc, c.debut DESC, b.etudiant_id ASC, a.critere_id ASC');

      return $q->execute();
    }

    /* Calcule les moyennes pour chaque évaluation numérique pour un terrain de stage */
    public function calcMoyenne($terrain)
    {
      $evals = $this->getEvalsRadio($terrain);

      $criteres_moyenne = array();

      foreach($evals as $eval)
      {
        if(!isset($criteres_moyenne[$eval->getGessehCritere()->getId()]['total'])) $criteres_moyenne[$eval->getGessehCritere()->getId()]['total'] =0;
        if(!isset($criteres_moyenne[$eval->getGessehCritere()->getId()]['evals'])) $criteres_moyenne[$eval->getGessehCritere()->getId()]['evals'] =0;
        $criteres_moyenne[$eval->getGessehCritere()->getId()]['titre'] = $eval->getGessehCritere()->getTitre();
        $criteres_moyenne[$eval->getGessehCritere()->getId()]['total'] += $eval->getValeur();
        $criteres_moyenne[$eval->getGessehCritere()->getId()]['ratio'] = $eval->getGessehCritere()->getRatio();
        $criteres_moyenne[$eval->getGessehCritere()->getId()]['evals'] ++;
        $criteres_moyenne[$eval->getGessehCritere()->getId()]['moyenne'] = round($criteres_moyenne[$eval->getGessehCritere()->getId()]['total'] / $criteres_moyenne[$eval->getGessehCritere()->getId()]['evals'], 1);
      }

      return $criteres_moyenne;
    }

    /* Récupère les commentaires d'un terrain de stage */
    public function getEvalsComments($terrain)
    {
      $q = Doctrine_Query::create()
        ->from('GessehEval a')
        ->leftjoin('a.GessehStage b')
        ->leftjoin('b.GessehPeriode c')
        ->leftjoin('a.GessehCritere d')
        ->where('b.terrain_id = ?', $terrain)
        ->andWhere('d.type = ?', 'text')
        ->OrderBy('c.debut DESC, b.etudiant_id ASC, a.critere_id ASC');

      return $q->execute();
    }

    /* Récupère les commentaires de tous les terrains de stage */
    public function retrieveComments(Doctrine_Query $q)
    {
      $rootAlias = $q->getRootAlias();
      $q->leftjoin($rootAlias.'.GessehStage c')
        ->leftjoin('c.GessehPeriode d')
        ->leftjoin($rootAlias.'.GessehCritere g')
        ->leftjoin('c.GessehTerrain e')
        ->leftjoin('e.GessehHopital f')
        ->where('g.type = ?', 'text')
        ->orderBy($rootAlias.'.created_at desc');

      return $q;
    }
}
