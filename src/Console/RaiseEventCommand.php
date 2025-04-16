<?php

namespace LaravelBeanstalkBus\Console;

use bus\config\ConfigNotFoundException;
use bus\exception\BrokerNotFoundException;
use bus\impl\TEvent;
use bus\MessageBus;
use Illuminate\Console\Command;
use Pheanstalk\Exception\NoImplementationException;

class RaiseEventCommand extends Command
{
    protected $signature = 'bus:event';
    protected $description = 'Raise new event';

    /**
     * @throws ConfigNotFoundException
     * @throws BrokerNotFoundException
     * @throws NoImplementationException
     */
    public function handle(MessageBus $messageBus): void
    {
        $messageBus->dispatch(new TEvent(1));
    }
}
