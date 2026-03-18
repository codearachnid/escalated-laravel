<?php

namespace Escalated\Laravel\Bridge\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

/**
 * Generic broadcast event emitted by plugin ctx.broadcast.* calls.
 *
 * The plugin runtime calls ctx.broadcast.toChannel / toUser / toTicket and
 * the ContextHandler dispatches this event via Laravel's broadcasting system.
 */
class PluginBroadcastEvent implements ShouldBroadcastNow
{
    public function __construct(
        private readonly string $channelName,
        private readonly string $eventName,
        private readonly array $payload,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel($this->channelName);
    }

    public function broadcastAs(): string
    {
        return $this->eventName;
    }

    public function broadcastWith(): array
    {
        return $this->payload;
    }
}
