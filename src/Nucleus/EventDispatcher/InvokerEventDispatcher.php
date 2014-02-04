<?php


namespace Nucleus\EventDispatcher;

use Nucleus\Invoker\IInvoker;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Symfony\Component\EventDispatcher\Event as BaseEvent;

class InvokerEventDispatcher extends ContainerAwareEventDispatcher
{
    /**
     * @var \Nucleus\Invoker\IInvoker
     */
    protected $invoker;

    public function __construct(ContainerInterface $container, IInvoker $invoker)
    {
        parent::__construct($container);
        $this->invoker = $invoker;
    }

    public function notify($subject,$eventName, $parameters = array())
    {
        return $this->dispatch($eventName,new Event($subject,$parameters));
    }

    /**
     * Triggers the listeners of an event.
     *
     * This method can be overridden to add functionality that is executed
     * for each listener.
     *
     * @param callable[] $listeners The event listeners.
     * @param string     $eventName The name of the event to dispatch.
     * @param Event      $event     The event object to pass to the event handlers/listeners.
     */
    protected function doDispatch($listeners, $eventName, BaseEvent $event)
    {
        $dispatcher = $this;
        foreach ($listeners as $listener) {
            $parameters = $this->getEventParameters($event);
            $parameters = array_merge(compact('eventName','event','dispatcher'),$parameters);
            $this->invoker->invoke($listener,$parameters);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
    }

    protected function getEventParameters($event)
    {
        if($event instanceof IEvent) {
            $parameters = $event->getParameters();
            $parameters['subject'] = $event->getSubject();
        } else {
            $parameters = array();
        }

        return $parameters;
    }
}