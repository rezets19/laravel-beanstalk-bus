<?php

namespace LaravelBeanstalkBus\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use LaravelBeanstalkBus\Factory\LaravelListenerFactory;

class ListenerCommand extends Command
{
    protected $signature = 'bus:listen {queue}';
    protected $description = 'Start listener for queue';

    public function handle(LaravelListenerFactory $listenerFactory, Application $app): void
    {
        $listenerFactory->create($this->argument('queue'), $app->storagePath('logs'))->listen();
    }
}
