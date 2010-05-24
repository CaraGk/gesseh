<?php

/**
 * services actions.
 *
 * @package    gesseh
 * @subpackage services
 * @author     Pierre-François "Pilou/ Angrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class servicesActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
     if($request->getParameter('order') == 'asc')
       $this->order = 'desc';
     else
       $this->order = 'asc';

     $this->gesseh_terrains = Doctrine_Core::getTable('GessehTerrain')->getListeTerrains($request->getParameter('tri'), $request->getParameter('order'));
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->gesseh_terrain = Doctrine::getTable('GessehTerrain')->getTerrainUnique($request);
    $this->forward404Unless($this->gesseh_terrain);

    $this->gesseh_evals = Doctrine::getTable('GessehEval')->calcMoyenne($request);
    $this->gesseh_comments = Doctrine::getTable('GessehEval')->getEvalsComments($request->getParameter('id'));
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
