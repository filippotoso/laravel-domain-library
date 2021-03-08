<?php

namespace FilippoToso\Domain\Traits;

trait Subscribeable
{
    /**
     * Automagically subscribe to existing event classes for the current model
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $tokens = explode('\\', get_class($this));
        $model = last($tokens);
        $tokens = array_slice($tokens, 0, length($tokens) - 2);
        $namespace = implode('\\', $tokens) . '\\Events\\' . $model . '\\';

        $events = [
            'retrieved', 'creating', 'created', 'updating', 'updated', 'saving',
            'saved', 'deleting', 'deleted', 'restoring', 'restored', 'replicating'
        ];

        foreach ($events as $event) {
            $eventClass = $namespace . $model . ucfirst($event) . 'Event';
            if (class_exists($eventClass)) {
                $this->dispatchesEvents[$event] = $eventClass;
            }
        }

        parent::__construct($attributes);
    }
}
