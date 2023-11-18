<?php

namespace LaravelBeanstalkBus\Factory;

use bus\factory\ListenerFactory;
use bus\handler\IHandler;
use bus\interfaces\APMSenderInterface;
use bus\Listener;
use bus\MessageBus;
use Psr\Log\LoggerInterface;

class LaravelListenerFactory
{
    public function create(string $queueName, string $tmpPath): Listener
    {
        return (new ListenerFactory())->create(
            $queueName,
            $tmpPath,
            app(MessageBus::class),
            app(LoggerInterface::class),
            app(APMSenderInterface::class),
            app(IHandler::class)
        );
    }
}
