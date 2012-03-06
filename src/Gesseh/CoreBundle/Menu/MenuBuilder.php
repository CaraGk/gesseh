<?php
// src/Gesseh/CoreBundle/Menu/MenuBuilder.php

namespace Gesseh\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

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
    $menu->setCurrentUri($request->getRequestUri());

    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Liste des terrains de stage')));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createStudentMenu(Request $request)
  {
    $menu = $this->factory->createItem('student');
    $menu->setCurrentUri($request->getRequestUri());

    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Liste des terrains de stage')));
    $menu->addChild('My places', array('route' => 'GCore_PIndex', 'label' => 'Mes stages', 'attributes' => array('title' => 'Mes stages en cours ou effectués')));
    $menu->addChild('Administrate', array('route' => 'admin_hp', 'label' => 'Administration', 'attributes' => array('title' => 'Administration du site')));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createAdminMenu(Request $request)
  {
    $menu = $this->factory->createItem('admin');
    $menu->setCurrentUri($request->getRequestUri());

    $menu->addChild('Home', array('route' => 'homepage', 'label' => 'Retour', 'attributes' => array('title' => 'Retour au site public')));
    $menu->addChild('Fieldset', array('route' => 'GCore_FSAIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Gérer les catégoriées, hôpitaux et services')));
    $menu->addChild('Student', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants et les promotions')));
    $menu->addChild('Placement', array('route' => 'GCore_PAIndex', 'label' => 'Stages', 'attributes' => array('title' => 'Gérer les stages')));

    return $menu;
  }
}
