<?php

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Nucleus\Bundle\CoreBundle\Application\IVariableRegistry;

class NucleusCoreExtensionTest extends WebTestCase
{
    public function testAnnotationGenerator()
    {
        $client = static::createClient();
        $this->assertTrue(AnnotationTagGenerator::$haveBeenRun);
        $object = new \ReflectionObject($client->getContainer());
        $this->assertTrue(strpos(file_get_contents($object->getFileName()),'/* This is a test for annotation generation */') !== false);
    }

    public function testEventDispatcher()
    {
        $client = static::createClient();
        $eventDispatcher = $client->getContainer()->get('event_dispatcher');
        $this->assertInstanceOf('Nucleus\Bundle\CoreBundle\EventDispatcher\InvokerEventDispatcher',$eventDispatcher);

        /* @var $eventDispatcher \Nucleus\Bundle\CoreBundle\EventDispatcher\InvokerEventDispatcher */
        $eventDispatcher->addListenerService('WebTestCase.test',array('test_listener_service','listen'));
        $event = $eventDispatcher->notify($this,'WebTestCase.test',array('param1'=>1));

        $this->assertTrue(ListenerService::$called);
        $this->assertSame($event, ListenerService::$event);
        $this->assertEquals(1, ListenerService::$param1);
        $this->assertEquals('WebTestCase.test', ListenerService::$eventName);
        $this->assertSame($this, ListenerService::$subject);
    }

    public function testVariableRegistry()
    {
        $client = static::createClient();
        $eventDispatcher = $client->getContainer()->get(IVariableRegistry::NUCLEUS_SERVICE_NAME);
        $this->assertInstanceOf('Nucleus\Bundle\CoreBundle\Application\IVariableRegistry',$eventDispatcher);
    }
}
