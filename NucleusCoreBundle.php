<?php

namespace Nucleus\Bundle\CoreBundle;

use Nucleus\Bundle\CoreBundle\DependencyInjection\NucleusCompilerPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NucleusCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new NucleusCompilerPass(), PassConfig::TYPE_OPTIMIZE);
    }
}