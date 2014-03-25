<?php

namespace Nucleus\Bundle\CoreBundle\DependencyInjection;

/**
 * Description of IAnnotationContainerGenerator
 *
 * @author Martin
 */
interface IAnnotationContainerGenerator
{
    public function processContainerBuilder(GenerationContext $context);
}
