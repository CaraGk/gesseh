<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Menu builder class
 */
class MenuBuilder
{
  private $factory;

  /**
   * @param FactoryInterface $factory
   */
  public function __construct(FactoryInterface $factory)
  {
    $this->factory = $factory;
  }

  /**
   * @param Request $request
   */
  public function createAnonMenu(Request $request)
  {
    $menu = $this->factory->createItem('anon');
    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Liste des terrains de stage')));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createStudentMenu(Request $request)
  {
    $menu = $this->factory->createItem('student');
    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Liste des terrains de stage')));
    $menu->addChild('My places', array('route' => 'GCore_PIndex', 'label' => 'Mes stages', 'attributes' => array('title' => 'Mes stages en cours ou effectués')));
    $menu->addChild('My wishes', array('route' => 'GSimul_SIndex', 'label' => 'Mes vœux', 'attributes' => array('title' => 'Mes vœux de stage pour les simulations')));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createAdminMenu(Request $request)
  {
    $menu = $this->factory->createItem('admin');
    $menu->addChild('Sectors', array('route' => 'GCore_FSASector', 'label' => 'Catégories', 'attributes' => array('title' => 'Gérer les catégories')));
    $menu->addChild('Students', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants')));
    $menu->addChild('Grades', array('route' => 'GUser_GAIndex', 'label' => 'Promotions', 'attributes' => array('title' => 'Gérer les promotions')));
    $menu->addChild('Placements', array('route' => 'GCore_PAPlacementIndex', 'label' => 'Stages', 'attributes' => array('title' => 'Gérer les stages')));
    $menu->addChild('Periods', array('route' => 'GCore_PAPeriodIndex', 'label' => 'Périodes de stage', 'attributes' => array('title' => 'Gérer les périodes de stage')));
    $menu->addChild('Simulation', array('route' => 'GSimul_SAList', 'label' => 'Simulations', 'attributes' => array('title' => 'Gérer les simulations')));
    $menu->addChild('SimRules', array('route' => 'GSimul_SARule', 'label' => 'Règles de simulation', 'attributes' => array('title' => 'Gérer les règles de simulation')));
    $menu->addChild('EvalForms', array('route' => 'GEval_AIndex', 'label' => 'Formulaires d\'évaluation', 'attributes' => array('title' => 'Gérer les formulaires d\'évaluation de stage')));
    $menu->addChild('Moderation', array('route' => 'GEval_ATextIndex', 'label' => 'Modérer', 'attributes' => array('title' => 'Modérer les évaluations de stage')));
    $menu->addChild('Parameters', array('route' => 'GParameter_PAIndex', 'label' => 'Paramètres', 'attributes' => array('title' => 'Gérer les paramètres du site')));

    return $menu;
  }

}
