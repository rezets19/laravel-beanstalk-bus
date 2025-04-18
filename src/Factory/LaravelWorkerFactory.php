<?php

namespace LaravelBeanstalkBus\Factory;

use bus\factory\WorkerFactory;
use bus\handler\HandlerInterface;
use bus\interfaces\APMSenderInterface;
use bus\MessageBus;
use bus\Worker;
use Illuminate\Contracts\Container\Container;
use Psr\Log\LoggerInterface;

class LaravelWorkerFactory
{
    public function __construct(private Container $app)
    {
    }

    public function create(string $queueName, string $tmpPath): Worker
    {
        return (new WorkerFactory())->create(
            queue: $queueName,
            tmpPath: $tmpPath,
            messageBus: $this->app->make(MessageBus::class),
            logger: $this->app->make(LoggerInterface::class),
            apm: $this->app->make(APMSenderInterface::class),
            handler: $this->app->make(HandlerInterface::class),
        );
    }
}
