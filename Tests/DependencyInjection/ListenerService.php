<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 14-02-04
 * Time: 17:06
 * To change this template use File | Settings | File Templates.
 */

namespace Nucleus\Bundle\CoreBundle\Tests\DependencyInjection;

use Nucleus\Bundle\CoreBundle\EventDispatcher\IEvent;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ListenerService
{
    public static $called = false;

    public static $subject;

    public static $eventName;

    public static $param1;

    public static $event;

    public function listen(WebTestCase $test,IEvent $event, $eventName, $param1)
    {
        static::$called = true;
        static::$subject = $test;
        static::$eventName = $eventName;
        static::$event = $event;
        static::$param1 = $param1;
    }
}