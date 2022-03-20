<?php

namespace FilippoToso\Domain\Livewire\Traits;

use Livewire\Livewire;

trait FormRequestable
{
    function validateResolved()
    {
        // Avoid validation on resolution if it's a Livewire request
        if (Livewire::isDefinitelyLivewireRequest()) {
            return false;
        }

        return parent::validateResolved();
    }
}
