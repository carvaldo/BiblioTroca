<?php

namespace App\TypeDefs;

class Diff
{
    private array $original;
    private array $changes;

    public function __construct(array $original = [], array $changes = []) {
        $this->original = $original;
        $this->changes = $changes;
    }

    public function getOriginal(): array {
        return $this->original;
    }

    public function getChanges(): array {
        return $this->changes;
    }

    public function __toString(): string
    {
        return json_encode([
            'Antes' => $this->original,
            'Depois' => $this->changes
        ] , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
