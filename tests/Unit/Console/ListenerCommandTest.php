<?php

namespace Tests\LaravelBeanstalkBus\Unit\Console;

use Illuminate\Contracts\Foundation\Application;
use LaravelBeanstalkBus\Console\ListenerCommand;
use LaravelBeanstalkBus\Factory\LaravelListenerFactory;
use PHPUnit\Framework\TestCase;
use Reflection\ReflectionHelper;
use Symfony\Component\Console\Input\InputInterface;

class ListenerCommandTest extends TestCase
{
    private ListenerCommand $listenerCommand;

    protected function setUp(): void
    {
        $reflectionHelper = new ReflectionHelper();

        $this->listenerCommand = new ListenerCommand();
        $this->input = $this->createMock(InputInterface::class);
        $reflectionHelper->setProperty($this->listenerCommand, 'input', $this->input);

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
