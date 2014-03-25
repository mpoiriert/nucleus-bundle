<?php

namespace Nucleus\Bundle\CoreBundle\EventDispatcher;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface IEventDispatcher extends EventDispatcherInterface
{
    public function notify($subject,$eventName, $parameters = array());
}