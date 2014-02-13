<?php

namespace Nucleus\Application;

use Nucleus\Application\IVariableRegistry;

/**
 * @author Martin Poirier Theoret <mpoiriert@gmail.com>
 */
class InMemoryVariableRegistry implements IVariableRegistry
{
    private $data = array();
    
    public function delete($name, $namespace = 'default')
    {
        unset($this->data[$namespace][$name]);
    }

    public function get($name, $default = null, $namespace = 'default')
    {
        if (!$this->has($name, $namespace)) {
            return $default;
        }

        return $this->data[$namespace][$name];
    }

    public function has($name, $namespace = 'default')
    {
        return isset($this->data[$namespace][$name]);
    }

    public function set($name, $value, $namespace = 'default')
    {
        $this->data[$namespace][$name] = $value;
    }
}
