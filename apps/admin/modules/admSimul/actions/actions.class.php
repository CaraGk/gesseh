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
  /* Supprime l'ancienne table de simulation et crée la nouvelle à partir du classement des étudiants */
  public function executeListUpdate(sfWebRequest $request)
  {
    Doctrine::getTable('GessehSimulation')->cleanSimulTable();
    Doctrine::getTable('GessehChoix')->cleanChoixTable();
    $count = Doctrine::getTable('GessehSimulation')->setSimulOrder();

    $this->getUser()->setFlash('notice', sprintf('%s étudiants enregistrés dans la table de simulation.', $count));

    $this->redirect('admSimul/index');
    }
  }
