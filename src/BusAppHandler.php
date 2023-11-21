<?php

namespace LaravelBeanstalkBus;

use bus\handler\IHandler;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use ReflectionException;
use ReflectionMethod;

class BusAppHandler implements IHandler
{
    public function __construct(private Application $app)
    {
    }

    /**
     * @param object $event
     * @param iterable $handlers
     * @return void
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function handle(object $event, iterable $handlers): void
    {
        foreach ($handlers as $item) {
            $this->call($event, $item[0], $item[1]);

        }
    }

    /**
     * @param object $event
     * @param mixed $closure
     * @param string $method
     * @return void
     * @throws ReflectionException
     * @throws BindingResolutionException
     */
    public function call(object $event, mixed $closure, string $method): void
    {
        if (is_string($closure)) {
            $handler = $this->app->make($closure);
            $reflectionMethod = new ReflectionMethod($handler, $method);
            $reflectionMethod->invoke($handler, $event);
        }
    }
}
