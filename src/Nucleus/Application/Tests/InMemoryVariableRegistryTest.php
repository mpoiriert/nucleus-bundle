<?php

namespace Nucleus\Application\Tests;

use Nucleus\Application\InMemoryVariableRegistry;

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