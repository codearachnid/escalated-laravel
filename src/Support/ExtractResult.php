<?php

namespace Escalated\Laravel\Support;

class ExtractResult
{
    public function __construct(
        /** Normalized records as associative arrays */
        public readonly array $records,
        /** Next cursor value, null when exhausted */
        public readonly ?string $cursor,
        /** Estimated total records, if available from API */
        public readonly ?int $totalCount = null,
    ) {}

    public function isExhausted(): bool
    {
        return $this->cursor === null;
    }
}
