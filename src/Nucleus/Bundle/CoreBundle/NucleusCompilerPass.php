<?php

namespace Nucleus\Bundle\CoreBundle;

use Nucleus\Bundle\CoreBundle\Annotation\IAnnotationContainerGenerator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nucleus\Annotation\AnnotationParser;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NucleusCompilerPass implements CompilerPassInterface
{

    /**
     * @var IAnnotationContainerGenerator[]
     */
    private $annotationContainerGenerators;

    /**
     * @var \Nucleus\Annotation\AnnotationParser
     */
    private $annotationParser;

    public function setAnnotationContainerBuilder($annotationName, IAnnotationContainerGenerator $annotationContainerGenerator)
    {
        $this->annotationContainerGenerators[trim($annotationName,'\\')] = $annotationContainerGenerator;
    }

    public function process(ContainerBuilder $container)
    {
        $annotationParser = $this->getAnnotationParser();
        foreach ($container->getDefinitions() as $id => $definition) {
            try {
                $parsingResult = $annotationParser->parse($definition->getClass());
            } catch(\Exception $e) {
                throw new \InvalidArgumentException('The service id [' . $id . '] with class [' . $definition->getClass() . '] cannot be parse',null,$e);
            }

            $annotations = $parsingResult->getAllAnnotations();

            foreach ($annotations as $parsingNode) {
                $generationContext = new GenerationContext($container, $id, $definition, $parsingNode);
                if(!is_null($builder = $this->getAnnotationContainerBuilder($parsingNode['annotation']))){
                    $container->addObjectResource($parsingNode['annotation'])->addObjectResource($builder);
                    $builder->processContainerBuilder($generationContext);
                }
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
     * @return \Nucleus\Annotation\AnnotationParser
     */
    private function getAnnotationParser()
    {
        if (is_null($this->annotationParser)) {
            $this->annotationParser = new AnnotationParser();
        }

        return $this->annotationParser;
    }
}