<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    private static $runned = false;

    public function __construct($environment, $debug)
    {
        if(!static::$runned) {
            $fileSystem = new Symfony\Component\Filesystem\Filesystem();
            $fileSystem->remove($this->getCacheDir());
            $fileSystem->remove($this->getLogDir());
            static::$runned = true;
        }
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        return array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Nucleus\Bundle\CoreBundle\NucleusCoreBundle()
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/NucleusCoreBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/NucleusCoreBundle/logs';
    }
}