<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\SlaBreached;
use Escalated\Laravel\Notifications\SlaBreachNotification;
use Escalated\Laravel\Support\ImportContext;

class SendSlaBreachNotification
{
    public function handle(SlaBreached $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        $ticket = $event->ticket;

        if ($ticket->assigned_to && $assignee = $ticket->assignee) {
            $assignee->notify(new SlaBreachNotification($ticket, $event->type));
        }
    }
}
