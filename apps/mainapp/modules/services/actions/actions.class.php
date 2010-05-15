<?php

/**
 * services actions.
 *
 * @package    gesseh
 * @subpackage services
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class servicesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
      $this->gesseh_terrains = Doctrine_Query::create()
      ->from('GessehTerrain a')
      ->leftjoin('a.GessehHopital b')
      ->addOrderBy('b.nom, a.filiere ASC')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
/*    $this->gesseh_terrain = Doctrine::getTable('GessehTerrain')->find(array($request->getParameter('id'))); */
    $this->gesseh_terrain = Doctrine_Query::create()
    ->from('GessehTerrain a')
    ->leftjoin('a.GessehHopital b')
    ->where('a.id = ?', $request->getParameter('id'))
    ->limit(1)
    ->fetchOne();
    $this->forward404Unless($this->gesseh_terrain);

    $this->gesseh_evals = Doctrine_Query::create()
    ->from('GessehEval a')
    ->leftjoin('a.GessehStage b')
    ->leftjoin('b.GessehPeriode c')
    ->leftjoin('a.GessehCritere d')
    ->where('b.terrain_id = ?', $request->getParameter('id'))
    ->addOrderBy('c.debut DESC, b.etudiant_id ASC, a.critere_id ASC')
    ->execute();
    $this->forward404Unless($this->gesseh_evals);
    
  }

/*  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehTerrainForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GessehTerrainForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_terrain = Doctrine::getTable('GessehTerrain')->find(array($request->getParameter('id'))), sprintf('Object gesseh_terrain does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehTerrainForm($gesseh_terrain);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_terrain = Doctrine::getTable('GessehTerrain')->find(array($request->getParameter('id'))), sprintf('Object gesseh_terrain does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehTerrainForm($gesseh_terrain);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_terrain = Doctrine::getTable('GessehTerrain')->find(array($request->getParameter('id'))), sprintf('Object gesseh_terrain does not exist (%s).', $request->getParameter('id')));
    $gesseh_terrain->delete();

    $this->redirect('services/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gesseh_terrain = $form->save();

      $this->redirect('services/edit?id='.$gesseh_terrain->getId());
    }
  } */
}
