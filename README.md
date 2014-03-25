Nucleus-Bundle
==============

[![Build Status](https://api.travis-ci.org/mpoiriert/nucleus-bundle.png?branch=master)](http://travis-ci.org/mpoiriert/nucleus-bundle)

This is a base bundle that is use by other bundle Nucleus bundle.

By itself it have a generic compiler pass to help modifying the container for annotation.

Also it does implement the [Nucleus\Invoker\IInvoker](https://github.com/mpoiriert/invoker) as a service.

It also redefined the event_dispatcher service for the class Nucleus\Bundle\CoreBundle\EventDispatcher\InvokerEventDispatcher.

NucleusCompilerPass
-------------------

If you need to do something base on annotation you can use the NucleusCompilerPass to simplify your life.

You must define a annotation like documented in the [doctrine annotation project](http://docs.doctrine-project.org/projects/doctrine-common/en/latest/reference/annotations.html).

Once this is done you must create a class that will implements Nucleus\Bundle\CoreBundle\DependencyInjection\IAnnotationContainerGenerator
that will receive a Nucleus\Bundle\CoreBundle\GenerationContext

Than you need to add a mapping between your annotation and the generator in the nucleus_core configuration.

    nucleus_core:
        annotation_container_generators:
            test_annotation:
                annotationClass: Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\Annotation
                generatorClass: Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotationTagGenerator

From there you can do what ever you want like you were in a compiler pass.

You also have some static method in Nucleus\Bundle\CoreBundle\DependencyInjection\Definition that could help you.

If you want a example you can check the test class Nucleus\Bundle\CoreBundle\Tests\DependencyInjection\AnnotationTagGenerator
and also the [mpoiriert/binder-bundle](https://github.com/mpoiriert/binder-bundle)

Nucleus\Bundle\CoreBundle\EventDispatcher\InvokerEventDispatcher
----------------------------------------------------------------

The way you must should use the new event dispatcher is by using the InvokerEventDispatcher::notify() method who does
receive a $subject, $eventName and $parameters as parameter. The Invoker will map the parameters to the "listeners" of
the event. The created "Event" object is the return value. You should not create typed Event by this means since the
service should not manipulate the event by itself (unless you want to stop the propagation). It does work like the
Request and a controller but with event.

You must also think about the way you will do unit test, no more Event faking just plane call. Also you could reuse a
method of a service to be call directly without having to redefine another method or a mediator for the event itself.

    <?php

     $eventDispatcher = $client->getContainer()->get('event_dispatcher');

     /* @var $eventDispatcher \Nucleus\Bundle\CoreBundle\EventDispatcher\InvokerEventDispatcher */
     $eventDispatcher->addListenerService('WebTestCase.test',array('test_listener_service','listen'));

     $user = new User();

     $event = $eventDispatcher->notify($user,'User.test',array('param1'=>1));

     class ListenerService
     {
         public function listen(User $user,IEvent $event, $eventName, $param1)
         {
             //...
         }
     }

The Invoker is in charge of mapping the $subject, $event, $eventName and any parameters pass to the event.

You can still create typed event but you will need to use the original EventDispatcherInterface::dispatch() method.
Be sure to extends from the Nucleus\Bundle\CoreBundle\EventDispatcher if you want to use the mapping functionality of parameters.
You could override the Event::getParameters() method an return processed parameter from the event.

