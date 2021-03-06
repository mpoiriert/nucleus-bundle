<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nucleus\Bundle\CoreBundle\Annotation\AnnotationParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NucleusCompilerPass implements CompilerPassInterface
{

    /**
     * @var IAnnotationContainerGenerator[]
     */
    private $annotationContainerGenerators = array();

    /**
     * @var AnnotationParser
     */
    private $annotationParser;

    public function setAnnotationContainerBuilder($annotationName, IAnnotationContainerGenerator $annotationContainerGenerator)
    {
        $this->annotationContainerGenerators[trim($annotationName,'\\')] = $annotationContainerGenerator;
    }

    private function loadAnnotationParser(ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processor = new Processor();
        $config = $processor->processConfiguration($configuration, $container->getExtensionConfig('nucleus_core'));
        foreach($config['annotation_container_generators'] as $generator) {
            $this->setAnnotationContainerBuilder(
                $generator['annotationClass'],
                new $generator['generatorClass']
            );
        }
    }

    public function process(ContainerBuilder $container)
    {
        $this->loadAnnotationParser($container);

        $annotationParser = $this->getAnnotationParser();
        foreach ($container->getDefinitions() as $id => $definition) {

            switch(true) {
                case !($class = $definition->getClass()):
                case $definition->getFactoryClass():
                case $definition->getFactoryService():
                case $definition->getFactoryMethod():
                    continue 2;
            }

            try {
                $parsingResult = $annotationParser->parse($class);
            } catch(\Exception $e) {
                throw new \InvalidArgumentException('The service id [' . $id . '] with class [' . $class . '] cannot be parse',null,$e);
            }

            $annotations = $parsingResult->getAllAnnotations();

            foreach ($annotations as $parsingNode) {
                if(is_null($builder = $this->getAnnotationContainerBuilder($parsingNode['annotation']))){
                    continue;
                }

                $generationContext = new GenerationContext($container, $id, $definition, $parsingNode);
                $container->addObjectResource($parsingNode['annotation'])->addObjectResource($builder);
                $builder->processContainerBuilder($generationContext);
            }
        }
    }

    /**
     * @param $annotation
     * @return IAnnotationContainerGenerator
     */
    private function getAnnotationContainerBuilder($annotation)
    {
        $annotationClass = get_class($annotation);
        if(array_key_exists($annotationClass,$this->annotationContainerGenerators)) {
            return  $this->annotationContainerGenerators[$annotationClass];
        }
    }

    /**
     * @return AnnotationParser
     */
    private function getAnnotationParser()
    {
        if (is_null($this->annotationParser)) {
            $this->annotationParser = new AnnotationParser();
        }

        return $this->annotationParser;
    }
}