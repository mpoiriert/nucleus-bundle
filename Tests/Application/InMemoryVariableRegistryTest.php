<?php

namespace Nucleus\Bundle\CoreBundle\Tests\Application;

use Nucleus\Bundle\CoreBundle\Application\InMemoryVariableRegistry;

/**
 * @author Martin Poirier Theoret <mpoiriert@gmail.com>
 */
class InMemoryVariableRegistryTest extends VariableRegistryTest
{
    protected function getApplicationVariableRegistry()
    {
        return new InMemoryVariableRegistry();
    }
}