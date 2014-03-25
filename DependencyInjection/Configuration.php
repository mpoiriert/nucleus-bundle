<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nucleus_core');

        $rootNode
            ->fixXmlConfig('annotation_container_generator')
            ->children()
                ->arrayNode('annotation_container_generators')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('annotationClass')
                                ->isRequired()
                                ->validate()
                                ->ifTrue(function($value) {return !class_exists($value,true);})
                                    ->thenInvalid('Class not found annotation [%s]')
                                ->end()
                            ->end()
                            ->scalarNode('generatorClass')
                                ->isRequired()
                                ->validate()
                                ->ifTrue(function($value) {return !class_exists($value,true);})
                                    ->thenInvalid('Class not found as annotation container generator [%s]')
                                ->ifTrue(function($value) {
                                        $reflection = new \ReflectionClass($value);
                                        return !$reflection->isSubclassOf(
                                            '\Nucleus\Bundle\CoreBundle\DependencyInjection\IAnnotationContainerGenerator'
                                        );
                                    })
                                    ->thenInvalid('Class [%s] must implement [Nucleus\Bundle\CoreBundle\DependencyInjection\IAnnotationContainerGenerator]')
                                ->end()
                            ->end()
                         ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
