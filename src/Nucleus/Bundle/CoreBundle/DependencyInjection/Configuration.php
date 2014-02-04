<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 14-02-03
 * Time: 11:49
 * To change this template use File | Settings | File Templates.
 */

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
                                            '\Nucleus\Bundle\CoreBundle\Annotation\IAnnotationContainerGenerator'
                                        );
                                    })
                                    ->thenInvalid('Class [%s] must implement [Nucleus\DependencyInjection\IAnnotationContainerGenerator]')
                                ->end()
                            ->end()
                         ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
