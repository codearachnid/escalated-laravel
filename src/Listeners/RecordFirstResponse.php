<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\ReplyCreated;
use Escalated\Laravel\Support\ImportContext;

class RecordFirstResponse
{
    public function handle(ReplyCreated $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        $reply = $event->reply;
        $ticket = $reply->ticket;

        // Only record first response by an agent (not the requester)
        if ($ticket->first_response_at === null
            && ! $reply->is_internal_note
            && $reply->author_type !== $ticket->requester_type
            || ($reply->author_type === $ticket->requester_type && $reply->author_id !== $ticket->requester_id)
        ) {
            $ticket->update(['first_response_at' => now()]);
        }
    }
}
