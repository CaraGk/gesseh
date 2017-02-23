<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Pierre-François ANGRAND <gesseh@medlibre.fr>
 * @copyright: Copyright 2013-2017 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack,
    Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use KDB\ParametersBundle\Model\ParameterManager;

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
     * @param RequestStack $requestStack
     * @param AuthorizationChecker $security
     */
    public function createMainMenu(RequestStack $requestStack, AuthorizationChecker $security, ParameterManager $pm)
    {
        $menu = $this->factory->createItem('main', array('navbar' => true));
        $session = $requestStack->getCurrentRequest()->getSession();

        $menu->addChild('Fieldset', array('route' => 'GCore_FSIndex', 'label' => 'Terrains de stage', 'attributes' => array('title' => 'Liste des terrains de stage')));

        if (!$security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            if ($pm->findParamByName('reg_active')->getValue() and $pm->findParamByName('reg_auto')->getValue()) {
                $menu->addChild('Register', array('route' => 'GRegister_URegister', 'label' => 'S\'inscrire', 'attributes' => array('title' => 'S\'inscrire et adhérer')));
            }
            $menu->addChild('Login', array('route' => 'fos_user_security_login', 'label' => 'S\'identifier', 'attributes' => array('title' => 'S\'identifier pour accéder au site')));
            $menu->addChild('LostPassword', array('route' => 'fos_user_resetting_request', 'label' => 'Mot de passe oublié', 'attributes' => array('title' => 'Réinitialiser le mot de passe')));
        } else {
            if ($security->isGranted('ROLE_STUDENT')) {
                $menu->addChild('Myself', array('route' => 'GUser_SShow', 'label' => 'Mon profil', 'attributes' => array('title' => 'Mes informations personnelles, mes stages en cours ou effectués, etc.')));
            }

            if ($security->isGranted('ROLE_PARTNER')) {
                $menu->addChild('Memberships', array('route' => 'GRegister_AIndex', 'label' => 'Adhésions', 'attributes' => array('title' => 'Afficher les adhésions en cours')));
            }

            if ($security->isGranted('ROLE_ADMIN')) {
                $adminMenu = $menu->addChild('Adminstration', array('label' => 'Administrer', 'dropdown' => true, 'caret' => true, 'icon' => 'king'));
                $adminMenu->addChild('Sectors', array('route' => 'GCore_FSASector', 'label' => 'Catégories', 'attributes' => array('title' => 'Gérer les catégories')));
                $adminMenu->addChild('Students', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants')));
                $adminMenu->addChild('Grades', array('route' => 'GUser_GAIndex', 'label' => 'Promotions', 'attributes' => array('title' => 'Gérer les promotions')));
                $adminMenu->addChild('Placements', array('route' => 'GCore_PAPlacementIndex', 'label' => 'Stages', 'attributes' => array('title' => 'Gérer les stages')));
                $adminMenu->addChild('Periods', array('route' => 'GCore_PAPeriodIndex', 'label' => 'Périodes de stage', 'attributes' => array('title' => 'Gérer les périodes de stage')));
                if ($pm->findParamByName('simul_active')->getValue()) {
                    $adminMenu->addChild('Simulation', array('route' => 'GSimul_SAList', 'label' => 'Simulations', 'attributes' => array('title' => 'Gérer les simulations')));
                    $adminMenu->addChild('SimRules', array('route' => 'GSimul_SARule', 'label' => 'Règles de simulation', 'attributes' => array('title' => 'Gérer les règles de simulation')));
                }
                if ($pm->findParamByName('eval_active')->getValue()) {
                    $adminMenu->addChild('EvalForms', array('route' => 'GEval_AIndex', 'label' => 'Formulaires d\'évaluation', 'attributes' => array('title' => 'Gérer les formulaires d\'évaluation de stage')));
                    $adminMenu->addChild('Moderation', array('route' => 'GEval_ATextIndex', 'label' => 'Modérer', 'attributes' => array('title' => 'Modérer les évaluations de stage')));
                }
                $adminMenu->addChild('Partners', array('route' => 'GRegister_PaIndex', 'label' => 'Partenaires', 'attributes' => array('title' => 'Gérer les accès des partenaires')));
                $adminMenu->addChild('Parameters', array('route' => 'GParameter_PAIndex', 'label' => 'Paramètres', 'attributes' => array('title' => 'Gérer les paramètres du site')));
            }

            if ($security->isGranted('ROLE_TEACHER')) {
                $menu->addChild('My department', array('route' => 'GCore_FSIndex', 'routeParameters' => array('limit' => array('type' => 'u.id', 'value' => '', 'description' => '')), 'label' => 'Mes terrains', 'attributes' => array('title' => 'Afficher les terrains de stage dont je suis responsable')));
            }

            if ($security->isGranted('ROLE_SUPERTEACHER')) {
                $adminMenu = $menu->addChild('Adminstration', array('label' => 'Administrer', 'dropdown' => true, 'caret' => true, 'icon' => 'king'));
                $adminMenu->addChild('Students', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants')));
                $adminMenu->addChild('EvalForms', array('route' => 'GEval_AIndex', 'label' => 'Formulaires d\'évaluation', 'attributes' => array('title' => 'Gérer les formulaires d\'évaluation de stage')));
                $adminMenu->addChild('Moderation', array('route' => 'GEval_ATextIndex', 'label' => 'Modérer', 'attributes' => array('title' => 'Modérer les évaluations de stage')));
            }

            $menu->addChild('Logout', array('route' => 'fos_user_security_logout', 'label' => 'Se déconnecter', 'attributes' => array('title' => 'Se déconnecter du site'), 'icon' => 'log-out'));
        }

        return $menu;
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
    $menu->addChild('My places', array('route' => 'GCore_PIndex', 'label' => 'Mes stages', 'attributes' => array('title' => 'Mes stages en cours ou effectués')));
    $menu->addChild('My wishes', array('route' => 'GSimul_SIndex', 'label' => 'Mes vœux', 'attributes' => array('title' => 'Mes vœux de stage pour les simulations')));
    $menu->addChild('My memberships', array('route' => 'GRegister_UIndex', 'label' => 'Mes adhésions', 'attributes' => array('title' => 'Mes adhésions à la structure')));

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

    /**
     * @param Request $request
     */
    public function createTeacherMenu(Request $request)
    {
        $menu = $this->factory->createItem('teacher');
        $menu->addChild('My department', array('route' => 'GCore_FSIndex', 'routeParameters' => array('limit' => array('type' => 'u.id', 'value' => '', 'description' => '')), 'label' => 'Mes terrains', 'attributes' => array('title' => 'Afficher les terrains de stage dont je suis responsable')));

        return $menu;
    }

    /**
     * @param Request $request
     */
    public function createSuperteacherMenu(Request $request)
    {
        $menu = $this->factory->createItem('superteacher');
        $menu->addChild('Students', array('route' => 'GUser_SAIndex', 'label' => 'Étudiants', 'attributes' => array('title' => 'Gérer les étudiants')));
        $menu->addChild('EvalForms', array('route' => 'GEval_AIndex', 'label' => 'Formulaires d\'évaluation', 'attributes' => array('title' => 'Gérer les formulaires d\'évaluation de stage')));
        $menu->addChild('Moderation', array('route' => 'GEval_ATextIndex', 'label' => 'Modérer', 'attributes' => array('title' => 'Modérer les évaluations de stage')));

        return $menu;
    }

}
