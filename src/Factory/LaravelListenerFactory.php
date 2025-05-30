<?php

namespace LaravelBeanstalkBus\Factory;

use bus\factory\ListenerFactory;
use bus\handler\HandlerInterface;
use bus\interfaces\APMSenderInterface;
use bus\Listener;
use bus\MessageBus;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

class LaravelListenerFactory
{
    public function __construct(private Container $app)
    {
    }

    public function create(string $queueName, string $tmpPath): Listener
    {
        return (new ListenerFactory())->create(
            queue: $queueName,
            tmpPath: $tmpPath,
            messageBus: $this->app->make(MessageBus::class),
            logger: $this->app->make(LoggerInterface::class),
            apm: $this->app->make(APMSenderInterface::class),
            handler: $this->app->make(HandlerInterface::class),
        );
    }
}
