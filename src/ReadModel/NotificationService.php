<?php

namespace App\ReadModel;

use App\ReadModel\WalletBalance\WalletBalanceProjection;
use App\ReadModel\WalletBalance\WalletBalanceWasChanged;
use Ecotone\EventSourcing\EventStore;
use Ecotone\Messaging\Attribute\Parameter\Reference;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\Attribute\QueryHandler;

final class NotificationService
{
    const GET_CURRENT_BALANCE_QUERY = "getCurrentBalance";

    #[QueryHandler(self::GET_CURRENT_BALANCE_QUERY)]
    public function getCurrentBalance(#[Reference] EventStore $eventStore): int
    {
        $projectionStreamName = "projection_" . WalletBalanceProjection::PROJECTION_NAME;
        if (!$eventStore->hasStream($projectionStreamName)) {
            return 0;
        }

        /** @var WalletBalanceWasChanged $event */
        $event = $eventStore->loadReverse($projectionStreamName, count: 1)[0]->getPayload();

        return $event->currentBalance;
    }

    #[EventHandler]
    public function when(WalletBalanceWasChanged $event): void
    {
        // we could for example send websocket message here
        echo sprintf("Balance after change is  %s\n", $event->currentBalance);
    }
}