<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketCreated;
use Escalated\Laravel\Notifications\NewTicketNotification;
use Escalated\Laravel\Support\ImportContext;

class SendNewTicketNotifications
{
    public function handle(TicketCreated $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        $ticket = $event->ticket;

        // Notify the requester
        if ($requester = $ticket->requester) {
            $requester->notify(new NewTicketNotification($ticket));
        }

        // Notify assigned agent if any
        if ($ticket->assigned_to && $assignee = $ticket->assignee) {
            $assignee->notify(new NewTicketNotification($ticket));
        }
    }
}
