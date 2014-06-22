<?php

namespace Levelup\Framework\Controller;

use Symfony\Component\HttpKernel\Controller\ControllerResolver as BaseControllerResolver;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * ControllerResolver.
 */
class ControllerResolver extends BaseControllerResolver
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct();
    }

    protected function createController($controller)
    {
        $count = substr_count($controller, ':');
        if (1 == $count) {
            // controller in the service:method notation
            list($class, $method) = explode(':', $controller, 2);
        } else {
            throw new \LogicException(sprintf('Unable to parse the controller name "%s".', $controller));
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();
        if ($controller instanceof ContainerAwareInterface) {
            $controller->setContainer($this->container);
        }

        return array($controller, $method);
    }
}
