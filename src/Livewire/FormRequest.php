<?php

namespace FilippoToso\Domain\Livewire;

use FilippoToso\Domain\Livewire\Traits\FormRequestable;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    use FormRequestable;
}
