<?php

namespace LaravelBeanstalkBus;

use bus\config\Connection;
use bus\config\Provider;
use bus\factory\TagsFactory;
use bus\handler\HandlerInterface;
use bus\impl\NullAPMSender;
use bus\interfaces\APMSenderInterface;
use bus\MessageBus;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelBeanstalkBus\Console\ListenerCommand;
use LaravelBeanstalkBus\Console\RaiseEventCommand;
use LaravelBeanstalkBus\Console\WorkerCommand;
use Psr\Log\LoggerInterface;

class LaravelBeanstalkBusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/beanstalk-bus.php' => $this->app->configPath('beanstalk-bus.php'),
            __DIR__ . '/../config/beanstalk-bus-events.php' => $this->app->configPath('beanstalk-bus-events.php'),
        ], 'config');

        $this->commands([
            RaiseEventCommand::class,
            ListenerCommand::class,
            WorkerCommand::class,
        ]);
    }

    public function register(): void
    {
        $this->makeAPMSenderInterface();
        $this->makeHandler();
        $this->makeMessageBus();
    }

    protected function makeMessageBus(): void
    {
        $this->app->singleton(MessageBus::class, function (Application $app) {
            return new MessageBus(
                new Connection($this->app->make('config')->get('beanstalk-bus.host'), $this->app->make('config')->get('beanstalk-bus.port')),
                new Provider(include $this->app->configPath('beanstalk-bus-events.php')),
                $app->make(LoggerInterface::class),
                $app->make(APMSenderInterface::class),
                $app->make(TagsFactory::class),
                $app->make(HandlerInterface::class)
            );
        });
    }

    protected function makeAPMSenderInterface(): void
    {
        $this->app->singleton(APMSenderInterface::class, function (Application $app) {
            return new NullAPMSender();
        });
    }

    protected function makeHandler()
    {
        $this->app->bind(HandlerInterface::class, function (Application $app) {
            return $app->make(BusAppHandler::class);
        });
    }
}
