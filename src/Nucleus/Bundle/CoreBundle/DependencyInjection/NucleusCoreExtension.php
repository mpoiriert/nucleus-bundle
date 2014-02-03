<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

use Nucleus\Bundle\CoreBundle\NucleusCompilerPass;
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
        $compilerPass = new NucleusCompilerPass();

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        foreach($config['annotation_container_generators'] as $generator) {
            $compilerPass->setAnnotationContainerBuilder(
                $generator['annotationClass'],
                new $generator['generatorClass']
            );
        }

        $container->addCompilerPass($compilerPass);
    }

    public function getAlias()
    {
        return 'nucleus_core';
    }
}