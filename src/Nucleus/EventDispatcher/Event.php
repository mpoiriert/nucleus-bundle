<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 14-02-04
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */

namespace Nucleus\EventDispatcher;

class Event extends \Symfony\Component\EventDispatcher\Event implements IEvent
{
    public function __construct($subject, array $parameters = array())
    {
        $this->subject = $subject;
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function hasParameter($name)
    {
        return array_key_exists($name, $this->parameters);
    }

    public function getParameter($name)
    {
        if(!$this->hasParameter($name)) {
            return null;
        }

        return $this->parameters[$name];
    }

    public function getSubject()
    {
        return $this->subject;
    }
}