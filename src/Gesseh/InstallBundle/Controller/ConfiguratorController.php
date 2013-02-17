<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gesseh\InstallBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\DBAL\Migrations\Migration,
  Doctrine\DBAL\Migrations\Configuration\Configuration;
use Gesseh\ParameterBundle\Entity\Parameter;
use Gesseh\UserBundle\Entity\Student,
  Gesseh\UserBundle\Form\StudentType,
  Gesseh\UserBundle\Form\StudentHandler;

/**
 * ConfiguratorController.
 *
 * @author Fabien Potencier <fabien@symfony.com>
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
            $form->bindRequest($request);
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
      $configuration->setMigrationsDirectory('/home/pilou/Public/gesseh/app/DoctrineMigrations');
      $configuration->registerMigrationsFromDirectory('/home/pilou/Public/gesseh/app/DoctrineMigrations');

      $migration = new Migration($configuration);

      $executedMigrations = $configuration->getMigratedVersions();
      $availableMigrations = $configuration->getAvailableVersions();
      $executedUnavailableMigrations = array_diff($executedMigrations, $availableMigrations);

      if ($executedUnavailableMigrations) {
        $warning = 'WARNING! You have ' . count($executedUnavailableMigrations) . ' previously executed migrations in the database that are not registered migrations : <ul>';
        foreach ($executedUnavailableMigrations as $executedUnavailableMigration) {
          $warning .= '<li>' . $executedUnavailableMigration . '</li>';
        }
        $warning .= '</ul>';
        $infos[0] = $warning;
      } else {
        $sql = $migration->migrate(null, null);

        if ( ! $sql) {
          $infos[0] = 'No migrations to execute.';
        } else {
          $infos[0] = 'Création des tables réussie.';

          // création et import des données de base : administrateur
        }
      }

//      return new RedirectResponse($this->container->get('router')->generate('_configurator_final'));
        return $this->container->get('templating')->renderResponse('GessehInstallBundle:Configurator:migrate.html.twig', array(
          'sql'  => $sql,
          'infos' => $infos,
        ));
    }

    public function createConfigAction()
    {
      $parameters = array(
        array(
          'Name'     => 'title',
          'Label'    => 'Nom du site',
          'Category' => 'General',
          'Type'     => 1,
        ),
        array(
          'Name'     => 'eval_active',
          'Label'    => 'Activer le module d\'évaluation',
          'Category' => 'Evaluation',
          'Type'     => 2,
        ),
        array(
          'Name'     => 'simul_active',
          'Label'    => 'Activer le module de simulation',
          'Category' => 'Simulation',
          'Type'     => 2,
        ),
      );

      foreach($parameters as $parameter) {
        $param = new Parameter();
        foreach($parameter as $key=>$value) {
          $func = 'set' . $key;
          $param->$func($value);
        }
      }

      return new RedirectResponse($this->container->get('router')->generate('_configurator_admin'));
      $this->get('session')->setFlash('notice', 'Paramètres du site créés.');
    }

    public function createAdminAction()
    {
      $em = $this->container->get('doctrine')->getEntityManager();
      $admin = new Student();
      $form = $this->container->get('form.factory')->create(new StudentType(false), $admin);
      $formHandler = new StudentHandler($form, $this->container->get('request'), $em, $this->container->get('fos_user.user_manager'));

      if($formHandler->process()) {
        $this->container->get('session')->setFlash('notice', 'Administrateur "' . $admin . '" enregistré.');
        return new RedirectResponse($this->container->get('router')->generate('GCore_FSIndex'));
      }

      return $this->container->get('templating')->renderResponse('GessehInstallBundle:Configurator:admin.html.twig', array(
        'admin_form' => $form->createView(),
      ));
    }

}
