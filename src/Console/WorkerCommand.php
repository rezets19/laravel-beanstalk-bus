<?php

namespace LaravelBeanstalkBus\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use LaravelBeanstalkBus\Factory\LaravelWorkerFactory;

class WorkerCommand extends Command
{
    protected $signature = 'bus:worker {queue}';
    protected $description = 'Start worker for queue';

    public function handle(LaravelWorkerFactory $workerFactory, Application $app): void
    {
        $workerFactory->create($this->argument('queue'), $app->storagePath('logs'))->listen();
    }
}
