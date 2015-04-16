<?php

/**
 * This file is part of the Accard package.
 *
 * (c) University of Pennsylvania
 *
 * For the full copyright and license information, please view the
 * LICENSE file that was distributed with this source code.
 */
namespace Accard\Bundle\CoreBundle\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Accard base application kernel.
 *
 * @author Frank Bardon Jr. <bardonf@upenn.edu>
 */
abstract class Kernel extends BaseKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new \Accard\Bundle\ResourceBundle\AccardResourceBundle(),
            new \Accard\Bundle\SettingsBundle\AccardSettingsBundle(),
            new \Accard\Bundle\CoreBundle\AccardCoreBundle(),
            new \Accard\Bundle\OptionBundle\AccardOptionBundle(),
            new \Accard\Bundle\PrototypeBundle\AccardPrototypeBundle(),
            new \Accard\Bundle\FieldBundle\AccardFieldBundle(),
            new \Accard\Bundle\DrugBundle\AccardDrugBundle(),
            new \Accard\Bundle\PatientBundle\AccardPatientBundle(),
            new \Accard\Bundle\DiagnosisBundle\AccardDiagnosisBundle(),
            new \Accard\Bundle\PhaseBundle\AccardPhaseBundle(),
            new \Accard\Bundle\BehaviorBundle\AccardBehaviorBundle(),
            new \Accard\Bundle\AttributeBundle\AccardAttributeBundle(),
            new \Accard\Bundle\SampleBundle\AccardSampleBundle(),
            new \Accard\Bundle\RegimenBundle\AccardRegimenBundle(),
            new \Accard\Bundle\ActivityBundle\AccardActivityBundle(),
            new \Accard\Bundle\TemplateBundle\AccardTemplateBundle(),
            new \Accard\Bundle\WebBundle\AccardWebBundle(),
            new \Accard\Bundle\OutcomesBundle\AccardOutcomesBundle(),
            new \Accard\Bundle\RIDICBundle\AccardRIDICBundle(),

            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            //new \Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Lexik\Bundle\TranslationBundle\LexikTranslationBundle(),
            new \FOS\RestBundle\FOSRestBundle(),
            new \JMS\SerializerBundle\JMSSerializerBundle(),
            new \WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new \Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \FOS\ElasticaBundle\FOSElasticaBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new \Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new \Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new \Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new \Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new \Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle();
        }

        return $bundles;
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $rootDir = $this->getRootDir();
        $loader->load($rootDir.'/config/config_'.$this->environment.'.yml');

        if (is_file($file = $rootDir.'/config/config_'.$this->environment.'.local.yml')) {
            $loader->load($file);
        }
    }
}
