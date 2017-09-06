<?php

namespace App\Traits;

trait Replicate
{
    protected function replicate()
    {
        $newCollection = new $this;
        foreach ($this as $item) {
            $newCollection->push(clone $item);
        }

        return $newCollection;
    }
}