<?php

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Nucleus\Bundle\CoreBundle\DependencyInjection\IAnnotationContainerGenerator;
use Nucleus\Bundle\CoreBundle\DependencyInjection\GenerationContext;
use Nucleus\Bundle\CoreBundle\DependencyInjection\Definition;

class AnnotationTagGenerator implements IAnnotationContainerGenerator
{
    static public $comment = '/* This is a test for annotation generation */';
    static public $haveBeenRun = false;

    public function processContainerBuilder(GenerationContext $context)
    {
        self::$haveBeenRun = true;
        Definition::setCodeInitialization($context->getServiceDefinition(),static::$comment);
    }
}
