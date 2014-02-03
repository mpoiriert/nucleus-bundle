<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nucleus\Bundle\CoreBundle\Annotation;
use Nucleus\Bundle\CoreBundle\GenerationContext;

/**
 * Description of IAnnotationContainerGenerator
 *
 * @author Martin
 */
interface IAnnotationContainerGenerator
{
    public function processContainerBuilder(GenerationContext $context);
}
