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
    $this->gesseh_periodes = Doctrine::getTable('GessehPeriode')
      ->createQuery('a')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->gesseh_periode = Doctrine::getTable('GessehPeriode')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->gesseh_periode);
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new GessehPeriodeForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new GessehPeriodeForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($gesseh_periode = Doctrine::getTable('GessehPeriode')->find(array($request->getParameter('id'))), sprintf('Object gesseh_periode does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehPeriodeForm($gesseh_periode);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($gesseh_periode = Doctrine::getTable('GessehPeriode')->find(array($request->getParameter('id'))), sprintf('Object gesseh_periode does not exist (%s).', $request->getParameter('id')));
    $this->form = new GessehPeriodeForm($gesseh_periode);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($gesseh_periode = Doctrine::getTable('GessehPeriode')->find(array($request->getParameter('id'))), sprintf('Object gesseh_periode does not exist (%s).', $request->getParameter('id')));
    $gesseh_periode->delete();

    $this->redirect('services/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $gesseh_periode = $form->save();

      $this->redirect('services/edit?id='.$gesseh_periode->getId());
    }
  }
}
