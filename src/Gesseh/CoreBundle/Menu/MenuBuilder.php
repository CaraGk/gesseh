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

    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex'));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createStudentMenu(Request $request)
  {
    $menu = $this->factory->createItem('student');
    $menu->setCurrentUri($request->getRequestUri());

    $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex'));
    $menu->addChild('Administrate', array('route' => 'admin_hp'));

    return $menu;
  }

  /**
   * @param Request $request
   */
  public function createAdminMenu(Request $request)
  {
    $menu = $this->factory->createItem('admin');
    $menu->setCurrentUri($request->getRequestUri());

    $menu->addChild('Home', array('route' => 'homepage'));
    $menu->addChild('Fieldset', array('route' => 'GCore_FSAIndex'));
    $menu->addChild('Student', array('route' => 'GUser_SAIndex'));

    return $menu;
  }
}
