<?php

namespace Spatie\EventProjector\Events;

use Exception;
use Spatie\EventProjector\StoredEvent;
use Spatie\EventProjector\EventHandlers\EventHandler;

final class EventHandlerFailedHandlingEvent
{
    /** @var \Spatie\EventProjector\EventHandlers\EventHandler */
    public $eventHandler;

    /** @var \Spatie\EventProjector\Models\EloquentStoredEvent */
    public $storedEvent;

    /** @var \Exception */
    public $exception;

    public function __construct(EventHandler $eventHandler, StoredEvent $storedEvent, Exception $exception)
    {
        $this->eventHandler = $eventHandler;

        $this->storedEvent = $storedEvent;

        $this->exception = $exception;
    }
}
