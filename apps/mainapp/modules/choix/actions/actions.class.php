<?php

/**
 * choix actions.
 *
 * @package    Gesseh
 * @subpackage choix
 * @author     Pierre-FrançoisPilouAngrand
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */

class choixActions extends sfActions
{
  /* Affiche les choix et permet de les modifier */
  public function executeEdit(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

		if(null !== $request->getParameter('up'))
		{
			Doctrine::getTable('GessehChoix')->setEtudiantChoixUp($userid, $request->getParameter('up'));
		}
		elseif(null !== $request->getParameter('down'))
		{
			Doctrine::getTable('GessehChoix')->setEtudiantChoixDown($userid, $request->getParameter('down'));
		}
		elseif(null !== $request->getParameter('del'))
		{
			Doctrine::getTable('GessehChoix')->setEtudiantChoixDel($userid, $request->getParameter('del'));
		}

		$this->gesseh_choix = Doctrine::getTable('GessehChoix')->getEtudiantChoix($userid);
    $this->form = new GessehChoixForm();
    $this->monchoix = Doctrine::getTable('GessehSimulation')->getSimulEtudiant($userid);
		$this->absents = Doctrine::getTable('GessehSimulation')->getAbsents($userid);
    if (null != $this->monchoix)
  		$this->autres = Doctrine::getTable('GessehChoix')->getMemeChoix($userid, $this->monchoix->getPoste());
    else
      $this->autres = null;
  }

  /* Valide l'ajout de choix et l'insère dans la base */
  public function executeUpdate(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
//		$this->forward404Unless($Gesseh_etudiant = Doctrine::getTable('GessehEtudiant')->find(array($this->getUser()->getUsername())), sprintf('Object Gesseh_etudiant does not exist (%s).', $request->getParameter('id')));
//    $this->form = new GessehEtudiantChoixForm($Gesseh_etudiant);
    $this->form = new GessehChoixForm();

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if($this->form->isValid())
		{
      if($choix = Doctrine::getTable('GessehChoix')->isAlreadySaved($userid, $this->form->getObject()->getPoste())) {
        $choix->setOrdre(null);
        $choix->save();
      } else {
        $this->form->save();
      }
      $this->redirect('@choix_edit');
    }

    $this->setTemplate('edit');
  }

  /* Lance la simulation pour une tranche d'étudiants */
	public function executeSimul(sfWebRequest $request)
	{
		if(!$debut = $request->getParameter('debut'))
			$debut = 1;

		$fin = $debut + csSettings::get('pager_simul');

		$simul = Doctrine::getTable('GessehSimulation')->simulChoixPager($debut, $fin);

		if($fin < Doctrine::getTable('GessehSimulation')->getMaxEtudiant())
			$this->redirect('@choix_simul?debut='.$fin);
		else
			$this->redirect('@choix_edit');
	}

  /* Supprime l'étudiant de la liste de simulation */
  public function executeAbsent(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

    $this->forward404Unless($simul = Doctrine::getTable('GessehSimulation')->getSimulEtudiant($userid));
    $simul->setAbsent(true);
    $simul->setPoste(null);
    $simul->setReste(null);
    $simul->save();

    $this->redirect('@choix_edit');
  }

  /* Réintroduit l'étudiant dans la liste de simulation */
  public function executePresent(sfWebRequest $request)
  {
    $userid = $this->getUser()->getEtudiantId();

    $this->forward404Unless($simul = Doctrine::getTable('GessehSimulation')->getSimulEtudiant($userid));
    $simul->setAbsent(false);
    $simul->save();

    $this->redirect('@choix_edit');
  }

}
