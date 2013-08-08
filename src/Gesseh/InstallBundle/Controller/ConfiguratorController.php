<?php

/**
 * This file is part of GESSEH project
 *
 * @author: Fabien Potencier <fabien@symfony.com>
 * @author: Pierre-François Angrand <caragk@angrand.fr>
 * @copyright: (c) Fabien Potencier <fabien@symfony.com>
 * @copyright: Copyright 2013 Pierre-François Angrand
 * @license: GPLv3
 * See LICENSE file or http://www.gnu.org/licenses/gpl.html
 */

namespace Gesseh\InstallBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\Migrations\Migration,
  Doctrine\DBAL\Migrations\Configuration\Configuration;
use Gesseh\ParameterBundle\Entity\Parameter;
use Gesseh\UserBundle\Entity\Student,
  Gesseh\UserBundle\Entity\Grade,
  Gesseh\UserBundle\Form\AdminType,
  Gesseh\UserBundle\Form\StudentHandler;

/**
 * ConfiguratorController.
 */
class ConfiguratorController extends ContainerAware
{
    /**
     * @return Response A Response instance
     */
    public function stepAction($index = 0)
    {
        $configurator = $this->container->get('gesseh.install.webconfigurator');

        $step = $configurator->getStep($index);
        $form = $this->container->get('form.factory')->create($step->getFormType(), $step);

        $request = $this->container->get('request');
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $configurator->mergeParameters($step->update($form->getData()));
                $configurator->write();

                $index++;

                if ($index < $configurator->getStepCount()) {
                    return new RedirectResponse($this->container->get('router')->generate('_configurator_step', array('index' => $index)));
                }

                return new RedirectResponse($this->container->get('router')->generate('_configurator_final'));
            }
        }

        return $this->container->get('templating')->renderResponse($step->getTemplate(), array(
            'form'    => $form->createView(),
            'index'   => $index,
            'count'   => $configurator->getStepCount(),
        ));
    }

    public function checkAction()
    {
        $configurator = $this->container->get('gesseh.install.webconfigurator');

        // Trying to get as much requirements as possible
        $majors = $configurator->getRequirements();
        $minors = $configurator->getOptionalSettings();

        $url = $this->container->get('router')->generate('_configurator_step', array('index' => 0));

        if (empty($majors) && empty($minors)) {
            return new RedirectResponse($url);
        }

        return $this->container->get('templating')->renderResponse('GessehInstallBundle::Configurator/check.html.twig', array(
            'majors'  => $majors,
            'minors'  => $minors,
            'url'     => $url,
        ));
    }

    public function finalAction()
    {
        $configurator = $this->container->get('gesseh.install.webconfigurator');
        $configurator->clean();

        try {
            $welcomeUrl = $this->container->get('router')->generate('_welcome');
        } catch (\Exception $e) {
            $welcomeUrl = null;
        }

        return $this->container->get('templating')->renderResponse('GessehInstallBundle::Configurator/final.html.twig', array(
            'welcome_url' => $welcomeUrl,
            'parameters'  => $configurator->render(),
            'ini_path'    => $this->container->getParameter('kernel.root_dir').'/config/parameters.ini',
            'is_writable' => $configurator->isFileWritable(),
        ));
    }

    public function migrateAction()
    {
      $conn = $this->container->get('database_connection');
      $configuration = new Configuration($conn);
      $configuration->setMigrationsNamespace('Application\Migrations');
      $configuration->setMigrationsTableName('migration_versions');
      $configuration->setMigrationsDirectory('/home/pilou/Public/gesseh/app/DoctrineMigrations');
      $configuration->registerMigrationsFromDirectory('/home/pilou/Public/gesseh/app/DoctrineMigrations');

      $executedMigrations = $configuration->getMigratedVersions();
      $availableMigrations = $configuration->getAvailableVersions();
      $newMigrations = count($availableMigrations) - count($executedMigrations);
      $executedUnavailableMigrations = array_diff($executedMigrations, $availableMigrations);

      if($newMigrations > 0) {
        $migration = new Migration($configuration);

        if ($executedUnavailableMigrations) {
          $warning = 'WARNING! You have ' . count($executedUnavailableMigrations) . ' previously executed migrations in the database that are not registered migrations : <ul>';
          foreach ($executedUnavailableMigrations as $executedUnavailableMigration) {
            $warning .= '<li>' . $executedUnavailableMigration . '</li>';
          }
          $warning .= '</ul>';
          $infos[0] = $warning;
        } else {
          if($infos[1] = $migration->migrate()) {
            $infos[0] = 'Création des tables réussie.';
            // création et import des données de base : administrateur
          } else {
            $infos[0] = 'No migrations to execute.';
          }
        }
      } else {
        $infos[0] = 'Votre base de données est déjà à jour.';
      }

//      return new RedirectResponse($this->container->get('router')->generate('_configurator_final'));
        return $this->container->get('templating')->renderResponse('GessehInstallBundle:Configurator:migrate.html.twig', array(
          'infos' => $infos,
        ));
    }

    public function createConfigAction()
    {
      $em = $this->container->get('doctrine')->getEntityManager();

      $parameters = array(
        array(
          'Name'     => 'title',
          'Label'    => 'Nom du site',
          'Category' => 'General',
          'Type'     => 1,
          'Value'    => 'Site d\'exemple',
          'Active'   => 1,
        ),
        array(
          'Name'     => 'eval_active',
          'Label'    => 'Activer le module d\'évaluation',
          'Category' => 'Evaluation',
          'Type'     => 2,
          'Value'    => true,
          'Active'   => 1,
        ),
        array(
          'Name'     => 'simul_active',
          'Label'    => 'Activer le module de simulation',
          'Category' => 'Simulation',
          'Type'     => 2,
          'Value'    => true,
          'Active'   => 1,
        ),
      );

      foreach($parameters as $parameter) {
        $param = new Parameter();
        foreach($parameter as $key=>$value) {
          $func = 'set' . $key;
          $param->$func($value);
        }
        $em->persist($param);
      }

      $grade = new Grade();
      $grade->setName('Hors promos');
      $grade->setRank(0);
      $grade->setIsActive(false);
      $em->persist($grade);

      $em->flush();

      return new RedirectResponse($this->container->get('router')->generate('_configurator_admin'));
      $this->get('session')->getFlashBag()->add('notice', 'Paramètres du site créés.');
    }

    public function createAdminAction()
    {
      $em = $this->container->get('doctrine')->getEntityManager();
      $admin = new Student();
      $form = $this->container->get('form.factory')->create(new AdminType(), $admin);
      $formHandler = new StudentHandler($form, $this->container->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if($formHandler->process()) {
        $this->container->get('session')->getFlashBag()->add('notice', 'Administrateur "' . $admin . '" enregistré.');
        return new RedirectResponse($this->container->get('router')->generate('GCore_FSIndex'));
      }

      return $this->container->get('templating')->renderResponse('GessehInstallBundle:Configurator:admin.html.twig', array(
        'admin_form' => $form->createView(),
      ));
    }

}
