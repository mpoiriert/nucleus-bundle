<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nucleus\Bundle\CoreBundle\Annotation;

/**
 * Description of BaseAnnotationContainerGenerator
 *
 * @author Martin
 */
abstract class BaseAnnotationContainerGenerator implements IAnnotationContainerGenerator
{
    public function processContainerBuilder(GenerationContext $context)
    {
        $this->generate($context, $context->getAnnotation());
    }
    
    abstract public function generate(GenerationContext $context, $annotation);
}
