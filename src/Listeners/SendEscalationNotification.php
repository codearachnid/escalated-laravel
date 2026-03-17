<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketEscalated;
use Escalated\Laravel\Notifications\TicketEscalatedNotification;
use Escalated\Laravel\Support\ImportContext;

class SendEscalationNotification
{
    public function handle(TicketEscalated $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        $ticket = $event->ticket;

        if ($ticket->assigned_to && $assignee = $ticket->assignee) {
            $assignee->notify(new TicketEscalatedNotification($ticket, $event->reason));
        }
    }
}
