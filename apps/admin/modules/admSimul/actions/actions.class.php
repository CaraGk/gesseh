<?php

require_once dirname(__FILE__).'/../lib/admSimulGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/admSimulGeneratorHelper.class.php';

/**
 * admSimul actions.
 *
 * @package    gesseh
 * @subpackage admSimul
 * @author     Pierre-François Pilou Angrand <tmp@angrand.fr>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class admSimulActions extends autoAdmSimulActions
{
  /* Lance la simulation de choix de postes */
  public function executeListSimul(sfWebRequest $request)
  {
    $simul = Doctrine::getTable('GessehSimulation')->simulChoixPager(1, 1000);

    $this->getUser()->setFlash('notice', sprintf('La simulation a été actualisée'));

    $this->redirect('admSimul/index');
  }

  /* Supprime l'ancienne table de simulation et crée la nouvelle à partir du classement des étudiants */
  public function executeListUpdate(sfWebRequest $request)
  {
    Doctrine::getTable('GessehSimulation')->cleanSimulTable();
    Doctrine::getTable('GessehChoix')->cleanChoixTable();
    $count = Doctrine::getTable('GessehSimulation')->setSimulOrder();

    $this->getUser()->setFlash('notice', sprintf('%s étudiants enregistrés dans la table de simulation.', $count));

    $this->redirect('admSimul/index');
  }

  /* Valide les résultats de la simulation et les copie dans les stages */
  public function executeListValid(sfWebRequest $request)
  {
    $periode = Doctrine::getTable('GessehPeriode')->getLastPeriodeId();
    Doctrine::getTable('GessehSimulation')->saveSimulTable($periode);

    $this->getUser()->setFlash('notice', sprintf('Les choix ont été sauvegardés en tant que stages'));

//    $this->redirect('admStage/index');
    $this->redirect('admSimul/index');
  }
}
