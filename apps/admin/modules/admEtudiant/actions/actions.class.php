<?php

require_once dirname(__FILE__).'/../lib/admEtudiantGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/admEtudiantGeneratorHelper.class.php';

/**
 * admEtudiant actions.
 *
 * @package    gesseh
 * @subpackage admEtudiant
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admEtudiantActions extends autoAdmEtudiantActions
{
  public function executeListAutoUpdatePromo()
  {
    $gesseh_promos = Doctrine::getTable('GessehPromo')
      ->createQuery('a')
      ->orderBy('a.ordre desc')
      ->execute();
    
    $promo_next = null;
    foreach($gesseh_promos as $gesseh_promo)
    {
      if($promo_next)
        Doctrine::getTable('GessehEtudiant')->changePromo($gesseh_promo->getId(), $promo_next);
      $promo_next = $gesseh_promo->getId();
    }

    $this->getUser()->setFlash('notice', sprintf('Tous les étudiants sont passés à la promotion supérieure.'));
    $this->redirect('admEtudiant/index');
  }

  public function executeListDeleteAncien()
  {
    $this->redirect('admEtudiant/index');
  }

  public function executeListImportNew()
  {
    $this->form = new ImportForm();
    $this->setTemplate('import');
  }

  public function executeImportcreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->form = new ImportForm();
    
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save('GessehEtudiant');
      $this->redirect('admEtudiant/index');
    }
    
    $this->setTemplate('import');
  }

}
