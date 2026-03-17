<?php

namespace Escalated\Laravel\Listeners;

use Escalated\Laravel\Events\TicketCreated;
use Escalated\Laravel\Services\SlaService;
use Escalated\Laravel\Support\ImportContext;

class AttachSlaPolicy
{
    public function __construct(protected SlaService $slaService) {}

    public function handle(TicketCreated $event): void
    {
        if (ImportContext::isImporting()) {
            return;
        }

        if (config('escalated.sla.enabled')) {
            $this->slaService->attachDefaultPolicy($event->ticket);
        }
    }
}
