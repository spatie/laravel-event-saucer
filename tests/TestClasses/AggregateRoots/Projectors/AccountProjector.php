<?php

namespace Spatie\EventProjector\Tests\TestClasses\AggregateRoots\Projectors;

use Exception;
use Spatie\EventProjector\Models\StoredEvent;
use Spatie\EventProjector\Projectors\Projector;
use Spatie\EventProjector\Projectors\ProjectsEvents;
use Spatie\EventProjector\Tests\TestClasses\AggregateRoots\DomainEvents\MoneyAdded;
use Spatie\EventProjector\Tests\TestClasses\Models\Account;

final class AccountProjector implements Projector
{
    use ProjectsEvents;

    protected $handlesEvents = [
        MoneyAdded::class => 'onMoneyAdded',
    ];

    public function onMoneyAdded(MoneyAdded $event, string $uuid)
    {
        $account = Account::firstOrCreate(compact('uuid'));

        $account->amount += $event->amount;

        $account->save();
    }
}
