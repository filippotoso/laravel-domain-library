<?php

namespace FilippoToso\Domain\Livewire\Traits;

use FilippoToso\Domain\Livewire\FormRequest;

trait Componentable
{
    public function validate($rules = null, $messages = [], $attributes = [])
    {
        if (is_a($rules, FormRequest::class)) {
            return parent::validate($rules->rules(), $rules->messages(), $rules->attributes());
        }

        return parent::validate($rules, $messages, $attributes);
    }

    public function validateOnly($field, $rules = null, $messages = [], $attributes = [], $dataOverrides = [])
    {
        if (is_a($rules, FormRequest::class)) {
            return parent::validateOnly($field, $rules->rules(), $rules->messages(), $rules->attributes());
        }

        return parent::validateOnly($field, $rules, $messages, $attributes, $dataOverrides);
    }

    public function getName()
    {
        $constant = static::class . '::ALIAS';
        return defined($constant) ? constant($constant) : parent::getName();
    }
}
