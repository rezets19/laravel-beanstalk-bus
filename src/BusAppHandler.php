<?php

namespace LaravelBeanstalkBus;

use bus\handler\IHandler;
use Illuminate\Foundation\Application;
use ReflectionMethod;

class BusAppHandler implements IHandler
{
    public function __construct(private Application $app)
    {
    }

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
     * @throws \ReflectionException
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
