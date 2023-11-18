<?php

namespace LaravelBeanstalkBus\Console;

use Illuminate\Console\Command;
use LaravelBeanstalkBus\Factory\LaravelListenerFactory;

class ListenerCommand extends Command
{
    protected $signature = 'bus:listen {queue}';
    protected $description = 'Start listener for queue';

    /**
     * @param LaravelListenerFactory $listenerFactory
     * @return void
     */
    public function handle(LaravelListenerFactory $listenerFactory): void
    {
        $listenerFactory->create($this->argument('queue'), storage_path('logs'))->listen();
    }
}
