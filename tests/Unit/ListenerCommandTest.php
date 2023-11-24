<?php

namespace Tests\Unit;

use Illuminate\Contracts\Foundation\Application;
use LaravelBeanstalkBus\Console\ListenerCommand;
use LaravelBeanstalkBus\Factory\LaravelListenerFactory;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\Console\Input\InputInterface;

class ListenerCommandTest extends TestCase
{
    private ListenerCommand $listenerCommand;

    protected function setUp(): void
    {
        $property = new ReflectionProperty(ListenerCommand::class, 'input');
        $property->setAccessible(true);
        $this->listenerCommand = new ListenerCommand();
        $this->input = $this->createMock(InputInterface::class);
        $property->setValue($this->listenerCommand, $this->input);

        $this->laravelListenerFactory = $this->createMock(LaravelListenerFactory::class);
        $this->application = $this->createMock(Application::class);
    }

    public function test_handle(): void
    {
        $this->input->expects(self::once())->method('getArgument')->willReturn('test');
        $this->laravelListenerFactory->expects(self::once())->method('create')->with('test');
        $this->application->expects(self::once())->method('storagePath')->willReturn('/tmp');

        $this->listenerCommand->handle(
            $this->laravelListenerFactory,
            $this->application
        );
    }
}
