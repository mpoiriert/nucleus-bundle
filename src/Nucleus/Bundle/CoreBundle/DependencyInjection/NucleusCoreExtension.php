<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

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
    }

    public function getAlias()
    {
        return 'nucleus_core';
    }
}