<?php

namespace LaravelBeanstalkBus;

use bus\handler\HandlerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use ReflectionException;
use ReflectionMethod;

class BusAppHandler implements HandlerInterface
{
    public function __construct(private Application $app)
    {
    }

    /**
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
