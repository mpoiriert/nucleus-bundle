<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Nucleus\Bundle\CoreBundle;

use Symfony\Component\DependencyInjection\Definition as BaseDefinition;

/**
 * Description of Definition
 *
 * @author Martin
 */
class Definition extends BaseDefinition
{
    private static $codeInitializationDecorator = '  
  $serviceContainer = $this;
  $service = $instance;
  $configurator = function() use ($serviceContainer, $service) {
    %code%
  };
  $configurator';

    private static $codeInitializations = array();

    static public function addCodeInitialization(BaseDefinition $definition, $code)
    {
        static::$codeInitializations[spl_object_hash($definition)] = static::getCodeInitalization($definition) . $code;
        static::updateConfigurator($definition);
    }

    static public function getCodeInitalization(BaseDefinition $definition)
    {
        $hash = spl_object_hash($definition);
        if(!array_key_exists($hash,static::$codeInitializations)) {
            static::$codeInitializations[$hash] = '';
        }

        return static::$codeInitializations[$hash];
    }

    static public function setCodeInitialization(BaseDefinition $definition, $code)
    {
        static::$codeInitializations[spl_object_hash($definition)] = $code;
        static::updateConfigurator($definition);
    }

    static private function updateConfigurator(BaseDefinition $definition)
    {
        $definition->setConfigurator(str_replace('%code%', static::getCodeInitalization($definition), self::$codeInitializationDecorator));
    }
}
