<?php

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Nucleus\Bundle\CoreBundle\Annotation\GenerationContext;
use Nucleus\Bundle\CoreBundle\Annotation\IAnnotationContainerGenerator;
use Nucleus\Bundle\CoreBundle\DependencyInjection\NucleusCoreExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

class NucleusCoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $container = new ContainerBuilder();
        $loader = new NucleusCoreExtension();
        $loader->load(array(
            'nucleus_core'=> array(
                'annotation_container_generators' => array(
                    array(
                        'annotationClass' => '\Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotationClass',
                        'generatorClass' => '\Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotationTagGenerator'
                    )
                )
            )
        ), $container);

        $definition = new Definition();
        $definition->setClass('Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotatedClass');
        $container->setDefinition('sfsdff',$definition);

        $container->compile();

        $this->assertTrue(AnnotationTagGenerator::$haveBeenRun);

        $dumper = new PhpDumper($container);
        $this->assertTrue(strpos($dumper->dump(),'/* This is a test for annotation generation */') !== false);
    }
}

class AnnotationTagGenerator implements IAnnotationContainerGenerator
{
    static public $comment = '/* This is a test for annotation generation */';
    static public $haveBeenRun = false;

    public function processContainerBuilder(\Nucleus\Bundle\CoreBundle\GenerationContext $context)
    {
        self::$haveBeenRun = true;
        \Nucleus\Bundle\CoreBundle\Definition::setCodeInitialization($context->getServiceDefinition(),static::$comment);
    }
}

/**
 * @Annotation
 */
class AnnotationClass
{}

/**
 * @\Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotationClass
 */
class AnnotatedClass
{}