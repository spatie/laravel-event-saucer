<?php

namespace Spatie\EventProjector\Tests;

use Spatie\EventProjector\AggregateRoot;
use Spatie\EventProjector\Models\StoredEvent;
use Spatie\EventProjector\Tests\TestClasses\AggregateRoots\AccountAggregateRoot;
use Spatie\EventProjector\Tests\TestClasses\AggregateRoots\DomainEvents\MoneyAdded;
use Spatie\EventProjector\Tests\TestClasses\FakeUuid;

final class AggregateRootTest extends TestCase
{
    /** @test */
    public function persisting_an_aggregate_root_will_persist_all_events_it_recorded()
    {
        $uuid = FakeUuid::generate();

        $aggregateRoot = AccountAggregateRoot::retrieve($uuid);

        $aggregateRoot->addMoney(100);

        $aggregateRoot->persist();

        $storedEvents  = StoredEvent::get();
        $this->assertCount(1, $storedEvents);

        $storedEvent = $storedEvents->first();
        $this->assertEquals($uuid, $storedEvent->uuid);

        $event = $storedEvent->event;
        $this->assertInstanceOf(MoneyAdded::class, $event);
        $this->assertEquals(100, $event->amount);
    }

    /** @test */
    public function when_retrieving_an_aggregate_root_all_events_will_be_replayed_to_it()
    {
        $uuid = FakeUuid::generate();

        /** @var \Spatie\EventProjector\Tests\TestClasses\AggregateRoots\AccountAggregateRoot $aggregateRoot */
        $aggregateRoot = AccountAggregateRoot::retrieve($uuid);

        $aggregateRoot->addMoney(100);
        $aggregateRoot->addMoney(100);
        $aggregateRoot->addMoney(100);

        $aggregateRoot->persist();

        $aggregateRoot = AccountAggregateRoot::retrieve($uuid);

        $this->assertEquals(300, $aggregateRoot->balance);
    }
}
