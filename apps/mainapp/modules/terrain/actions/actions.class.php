<?php

/**
 * terrain actions.
 *
 * @package    gesseh
 * @subpackage terrain
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class terrainActions extends sfActions
{
  /* Affiche la liste des terrains de stage disponibles */
  public function executeIndex(sfWebRequest $request)
  {
//     $this->tri = $this->changeTri($request);
//     $tri = null;

     if (Doctrine::getTable('GessehPeriode')->getActivePeriode() and csSettings::get('mod_choix') and $this->getUser()->isAuthenticated())
       $this->postes_restants = Doctrine::getTable('GessehSimulation')->updateTerrain(Doctrine::getTable('GessehTerrain')->getActiveTerrainTbl());
     else
       $this->postes_restants = null;

     $this->pager = new sfDoctrinePager('GessehTerrain', 30);
     $this->pager->setQuery(Doctrine::getTable('GessehTerrain')->getListeTerrains());
     $this->pager->setPage($request->getParameter('page', 1));
  }

  /* Affiche les informations relatives à un terrain de stage */
  public function executeShow(sfWebRequest $request)
  {
    $this->gesseh_terrain = Doctrine::getTable('GessehTerrain')->getTerrainUnique($request->getParameter('id'));
    $this->forward404Unless($this->gesseh_terrain);
    $this->bb_parser = new sfBBCodeParser();
  }

  /* Change les paramètres de tri de la liste des terrains de stage */
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

  /* Change l'ordre du paramètre de tri */
  private function changeOrder($order)
  {
    if ($order == 'asc')
      return 'desc';
    else
      return 'asc';
  }

}
