<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {   
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new InDaFlux\JQueryGanttBundle\InDaFluxJQueryGanttBundle(),
            new InDaFlux\TableTriableBundle\InDaFluxTableTriableBundle(),
            new Pericles3Bundle\Pericles3Bundle(),
            new Greywolfs\FineDiffBundle\GreywolfsPHPFineDiffBundle(),
            new InDaFlux\FontelloBundle\InDaFluxFontelloBundle(),
            new InDaFlux\JQueryUIBundle\InDaFluxJQueryUIBundle(),
            new InDaFlux\HtmlToDocWImgBundle\InDaFluxHtmlToDocWImgBundle(),
            new InDaFlux\SwissArmyKnifeBundle\InDaFluxSwissArmyKnifeBundle(),
            new fados\ChartjsBundle\ChartjsBundle(),
            new PUGX\AutocompleterBundle\PUGXAutocompleterBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
#            new FOS\UserBundle\FOSUserBundle(),

#            new InDaFlux\ \InDafluxIDSBundle(),
            
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
