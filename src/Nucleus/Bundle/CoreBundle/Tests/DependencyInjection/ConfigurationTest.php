<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 14-02-03
 * Time: 13:23
 * To change this template use File | Settings | File Templates.
 */

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;


use Nucleus\Bundle\CoreBundle\Annotation\GenerationContext;
use Nucleus\Bundle\CoreBundle\Annotation\IAnnotationContainerGenerator;
use Nucleus\Bundle\CoreBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function provideTestConfiguration()
    {
        return array(
            array(array(array()),null,'annotationClass'),
            array(array(array('annotationClass'=>null)),null,'generatorClass'),
            array(array(array('annotationClass'=>null,'generatorClass'=>'stdClass')),null,'Nucleus\DependencyInjection\IAnnotationContainerGenerator'),
            array(array(array('annotationClass'=>null,'generatorClass'=>'Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\GeneratorClass')))
        );
    }

    /**
     * @dataProvider provideTestConfiguration
     *
     * @param array $annotationConfiguration
     * @param array $expected
     * @param string $expectedException
     * @throws \Exception
     */
    public function testAnnotationContainerGeneratorsConfiguration(array $annotationConfiguration, array $expected = null, $expectedException = null)
    {
        $configurationTest = array(
            'nucleus_core' =>
            array(
                'annotation_container_generators' => $annotationConfiguration
            )
        );

        $processor = new Processor();
        $configuration = new Configuration();

        try {
            $processedConfiguration = $processor->processConfiguration(
                $configuration,
                $configurationTest
            );

            if(!is_null($expectedException)) {
                $this->fail('A exception with message containing [' . $expectedException . '] should have been thrown');
            }

            if($expected === null) {
                $expected = array(
                  'annotation_container_generators' => $annotationConfiguration
                );
            }

            $this->assertEquals($expected, $processedConfiguration);
            return;
        } catch(InvalidConfigurationException $e) {
            if(is_null($expectedException)) {
                throw $e;
            }
        }

        $this->assertTrue(
            strpos($e->getMessage(),$expectedException) !== false,
            'The exception message is not valid [' . $e->getMessage() . ']'
        );
    }
}

class GeneratorClass implements IAnnotationContainerGenerator
{

    public function processContainerBuilder(\Nucleus\Bundle\CoreBundle\GenerationContext $context)
    {
        // TODO: Implement processContainerBuilder() method.
    }
}