<?php

namespace LaravelBeanstalkBus;

use bus\config\Connection;
use bus\config\Provider;
use bus\factory\TagsFactory;
use bus\handler\IHandler;
use bus\impl\NullAPMSender;
use bus\interfaces\APMSenderInterface;
use bus\MessageBus;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use LaravelBeanstalkBus\Console\ListenerCommand;
use LaravelBeanstalkBus\Console\RaiseEventCommand;
use Psr\Log\LoggerInterface;

class LaravelBeanstalkBusServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/laravel-beanstalk-bus.php' => config_path('laravel-beanstalk-bus.php'),
            __DIR__ . '/../config/beanstalk-bus-events-config.php' => config_path('beanstalk-bus-events-config.php'),
        ], 'config');

        $this->commands([
            RaiseEventCommand::class,
            ListenerCommand::class,
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
                new Connection(config('laravel-beanstalk-bus.host'), config('laravel-beanstalk-bus.port')),
                new Provider(include config_path('beanstalk-bus-events-config.php')),
                $app->make(LoggerInterface::class),
                $app->make(APMSenderInterface::class),
                $app->make(TagsFactory::class),
                $app->make(IHandler::class)
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
        $this->app->bind(IHandler::class, function (Application $app) {
            return $app->make(BusAppHandler::class);
        });
    }
}
