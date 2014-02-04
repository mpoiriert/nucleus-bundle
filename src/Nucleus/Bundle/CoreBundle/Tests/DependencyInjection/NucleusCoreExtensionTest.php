<?php

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NucleusCoreExtensionTest extends WebTestCase
{
    public function test()
    {
        $client = static::createClient();
        $this->assertTrue(AnnotationTagGenerator::$haveBeenRun);
        $object = new \ReflectionObject($client->getContainer());
        $this->assertTrue(strpos(file_get_contents($object->getFileName()),'/* This is a test for annotation generation */') !== false);
    }
}
