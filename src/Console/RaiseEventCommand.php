<?php

namespace LaravelBeanstalkBus\Console;

use bus\config\ConfigNotFoundException;
use bus\exception\BrokerNotFoundException;
use bus\impl\TEvent;
use bus\MessageBus;
use Illuminate\Console\Command;

class RaiseEventCommand extends Command
{
    protected $signature = 'bus:event';
    protected $description = 'Raise new event';

    /**
     * @param MessageBus $messageBus
     * @return void
     * @throws ConfigNotFoundException
     * @throws BrokerNotFoundException
     */
    public function handle(MessageBus $messageBus): void
    {
        $messageBus->dispatch(new TEvent(1));
    }
}
