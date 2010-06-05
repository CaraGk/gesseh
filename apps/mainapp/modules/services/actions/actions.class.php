<?php

/**
 * services actions.
 *
 * @package    gesseh
 * @subpackage services
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class servicesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
//     $this->tri = $this->changeTri($request);
     $this->tri = null;
     $this->gesseh_terrains = Doctrine::getTable('GessehTerrain')->getListeTerrains($request);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->gesseh_terrain = Doctrine::getTable('GessehTerrain')->getTerrainUnique($request->getParameter('id'));
    $this->forward404Unless($this->gesseh_terrain);

    $this->gesseh_evals = Doctrine::getTable('GessehEval')->calcMoyenne($request);
    $this->gesseh_comments = Doctrine::getTable('GessehEval')->getEvalsComments($request->getParameter('id'));
  }

  private function changeTri($request)
  {
    if ($request->getParameter('tri1') == 'hopital')
    {
      $tri = array(
        'hopital' => 'tri1=hopital&order1='.$this->changeOrder($request->getParameter('order1')),
        'filiere' => 'tri1=filiere&order1=asc&tri2=hopital&order2='.$request->getParameter('order1')
      );
    }
    elseif ($request->getParameter('tri1') == 'filiere')
    {
      $tri = array(
        'filiere' => 'tri1=filiere&order1='.$this->changeOrder($request->getParameter('order1')),
	'hopital' => 'tri1=hopital&order1=asc&tri2=filiere&order2='.$request->getParameter('order1')
      );
    }
    else
    {
      $tri = array(
        'filiere' => 'tri1=filiere&order1=asc',
	'hopital' => 'tri1=hopital&order1=asc'
      );
    }

    return $tri;
  }

  private function changeOrder($order)
  {
    if ($order == 'asc')
      return 'desc';
    else
      return 'asc';
  }

}
