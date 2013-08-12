<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <caragk@angrand.fr>
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
    $menu->addChild('My wishes', array('route' => 'GSimulation_SIndex', 'label' => 'Mes vœux', 'attributes' => array('title' => 'Mes vœux de stage pour les simulations')));
    $menu->addChild('Administrate', array('route' => 'admin_hp', 'label' => 'Administration', 'attributes' => array('title' => 'Administration du site')));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createAdminMenu(Request $request)
  {
    $menu = $this->factory->createItem('admin');
    $menu->addChild('Home', array('route' => 'homepage', 'label' => 'Retour', 'attributes' => array('title' => 'Retour au site public')));
    $menu->addChild('Fieldset', array('route' => 'GCore_FSAHospital', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Gérer les catégoriées, hôpitaux et services')));
    $menu->addChild('Student', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants et les promotions')));
    $menu->addChild('Placement', array('route' => 'GCore_PAIndex', 'label' => 'Stages', 'attributes' => array('title' => 'Gérer les stages')));
    $menu->addChild('Simulation', array('route' => 'GSimulation_SAIndex', 'label' => 'Simulations', 'attributes' => array('title' => 'Gérer les simulations')));
    $menu->addChild('Rules', array('route' => 'GSimul_SAIndexRule', 'label' => 'Règles', 'attributes' => array('title' => 'Règles de simulation')));
    $menu->addChild('Evaluation', array('route' => 'GEval_AIndex', 'label' => 'Évaluations', 'attributes' => array('title' => 'Gérer les évaluations de stage')));
    $menu->addChild('Moderation', array('route' => 'GEval_ATextIndex', 'label' => 'Modération', 'attributes' => array('title' => 'Modérer les évaluations textuelles')));
    $menu->addChild('Parameters', array('route' => 'GParameter_PAIndex', 'label' => 'Paramètres', 'attributes' => array('title' => 'Gérer les paramètres du site')));

    return $menu;
  }

  public function createFieldSetAdminMenu(Request $request)
  {
    $menu = $this->factory->createItem('field_set_admin');
    $menu->addChild('Hospitals', array('route' => 'GCore_FSAHospital', 'label' => 'Hôpitaux et services', 'attributes' => array('title' => 'Gérer les hôpitaux et services associés')));
    $menu->addChild('Sectors', array('route' => 'GCore_FSAHospital', 'label' => 'Catégories de service', 'attributes' => array('title' => 'Gérer les catégories de services')));
    $menu->addChild('Parameters', array('route' => 'GParameter_PAIndex', 'label' => 'Paramètres', 'attributes' => array('title' => 'Gérer les paramètres des terrains de stage')));

    return $menu;
  }
}
