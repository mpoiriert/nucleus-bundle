<?php

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Nucleus\Bundle\CoreBundle\Annotation\IAnnotationContainerGenerator;

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
