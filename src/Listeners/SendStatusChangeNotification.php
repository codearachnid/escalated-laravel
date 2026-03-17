<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketStatusChanged;
use Escalated\Laravel\Notifications\TicketStatusChangedNotification;
use Escalated\Laravel\Support\ImportContext;

class SendStatusChangeNotification
{
    public function handle(TicketStatusChanged $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        $ticket = $event->ticket;

        if ($requester = $ticket->requester) {
            $requester->notify(new TicketStatusChangedNotification(
                $ticket, $event->oldStatus, $event->newStatus
            ));
        }

        // Notify followers (except the causer and requester)
        $ticket->loadMissing('followers');
        foreach ($ticket->followers as $follower) {
            if ($follower->getKey() !== $event->causer?->getKey()
                && $follower->getKey() !== $ticket->requester_id) {
                $follower->notify(new TicketStatusChangedNotification(
                    $ticket, $event->oldStatus, $event->newStatus
                ));
            }
        }
    }
}
