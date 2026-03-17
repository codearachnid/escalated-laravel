<?php

namespace Escalated\Laravel\Support;

class ImportContext
{
    private static bool $importing = false;

    public static function isImporting(): bool
    {
        return static::$importing;
    }

    /**
     * Run a callback with event suppression active.
     * All Escalated domain events, notifications, SLA timers,
     * and automations are suppressed while this is true.
     */
    public static function suppress(callable $callback): mixed
    {
        static::$importing = true;

        try {
            return $callback();
        } finally {
            static::$importing = false;
        }
    }
}
