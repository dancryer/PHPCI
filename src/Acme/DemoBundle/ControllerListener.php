<?php

namespace Acme\DemoBundle;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Acme\DemoBundle\Twig\Extension\DemoExtension;

class ControllerListener
{
    protected $extension;

    public function __construct(DemoExtension $extension)
    {
        $this->extension = $extension;
    }

    public function getController(Event $event, $controller)
    {
        if (HttpKernelInterface::MASTER_REQUEST === $event->get('request_type')) {
            $this->extension->setController($controller);
        }

        return $controller;
    }
}
