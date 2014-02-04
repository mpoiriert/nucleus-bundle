<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use \Symfony\Component\HttpKernel\DependencyInjection\Extension;
use \Symfony\Component\DependencyInjection\ContainerBuilder;

class NucleusCoreExtension extends Extension
{
    /**
     * Handles the nucleus_core configuration and a compiler pass
     *
     * @param array            $configs   The configurations being loaded
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new XmlFileLoader($container, $fileLocator);
        $loader->load('invoker.xml');
        $loader->load('event_dispatcher.xml');
    }

    public function getAlias()
    {
        return 'nucleus_core';
    }
}