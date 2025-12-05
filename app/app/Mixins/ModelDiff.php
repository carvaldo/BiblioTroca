<?php

namespace App\Mixins;

use App\Errors\AppException;
use App\TypeDefs\Diff;
use Illuminate\Database\Eloquent\Model;

trait ModelDiff
{
    public function diff(): ?Diff
    {
        $changes = $this->getDirty();
        if (empty($changes)) return null;
        $originalAux = $this->getOriginal();
        $original = [];
        foreach ($changes as $key => $value) {
            $original[$key] = $originalAux[$key];
        }
        return new Diff($original, $changes);
    }
}
