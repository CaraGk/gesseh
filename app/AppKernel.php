<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new KDB\ParametersBundle\KDBParametersBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Gesseh\CoreBundle\GessehCoreBundle(),
            new Gesseh\UserBundle\GessehUserBundle(),
            new Gesseh\SimulationBundle\GessehSimulationBundle(),
            new Gesseh\EvaluationBundle\GessehEvaluationBundle(),
            new Gesseh\ParameterBundle\GessehParameterBundle(),
            //            new Gesseh\MigrateOldDbBundle\GessehMigrateOldDbBundle(), // Bundle de migration des données de la version 0.2alpha
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new WhiteOctober\TCPDFBundle\WhiteOctoberTCPDFBundle(),
            new Gesseh\RegisterBundle\GessehRegisterBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\Payment\CoreBundle\JMSPaymentCoreBundle(),
            new JMS\Payment\PaypalBundle\JMSPaymentPaypalBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
