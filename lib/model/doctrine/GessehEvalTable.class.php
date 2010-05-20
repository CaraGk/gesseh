<?php


class GessehEvalTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GessehEval');
    }

    public function getEvals($request)
    {
      $q = $this->gesseh_evals = Doctrine_Query::create()
      ->from('GessehEval a')
      ->leftjoin('a.GessehStage b')
      ->leftjoin('b.GessehPeriode c')
      ->leftjoin('a.GessehCritere d')
      ->where('b.terrain_id = ?', $request->getParameter('id'))
      ->addOrderBy('c.debut DESC, b.etudiant_id ASC, a.critere_id ASC');

      return $q->execute();
    }

}
