<?php


class GessehEvalTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEval');
    }

    public function getEvalsFromStage($stage_id)
    {
      $q = $this->gesseh_evals = Doctrine_Query::create()
      ->from('GessehEval a')
      ->leftjoin('a.GessehStage b')
      ->leftjoin('b.GessehPeriode c')
      ->leftjoin('a.GessehCritere d')
      ->where('a.stage_id = ?', $stage_id)
      ->OrderBy('a.critere_id ASC');

      return $q->execute();
    }

    private function getEvalsRadio($request)
    {
      $q = $this->gesseh_evals = Doctrine_Query::create()
      ->from('GessehEval a')
      ->leftjoin('a.GessehStage b')
      ->leftjoin('b.GessehPeriode c')
      ->leftjoin('a.GessehCritere d')
      ->where('b.terrain_id = ?', $request->getParameter('id'))
      ->andWhere('d.type = ?', 'radio')
      ->OrderBy('c.debut DESC, b.etudiant_id ASC, a.critere_id ASC');
      
      return $q->execute();
    }

    public function calcMoyenne($request)
    {
      $evals = $this->getEvalsRadio($request);

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

    public function getEvalsComments($terrain)
    {
      $q = $this->gesseh_evals = Doctrine_Query::create()
      ->from('GessehEval a')
      ->leftjoin('a.GessehStage b')
      ->leftjoin('b.GessehPeriode c')
      ->leftjoin('a.GessehCritere d')
      ->where('b.terrain_id = ?', $terrain)
      ->andWhere('d.type = ?', 'text')
      ->OrderBy('c.debut DESC, b.etudiant_id ASC, a.critere_id ASC');
      
      return $q->execute();
    } 
}
