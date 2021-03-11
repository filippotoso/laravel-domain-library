<?php

namespace FilippoToso\Domain\Traits;

trait Collectionable
{
    /**
     * Automagically link the existing Collection class
     *
     * @param array $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        $tokens = explode('\\', get_class($this));
        $model = last($tokens);
        $tokens = array_slice($tokens, 0, count($tokens) - 2);
        $collectionClass = implode('\\', $tokens) . '\\Collections\\' . $model . 'Collection';

        if (class_exists($collectionClass)) {
            return new $collectionClass($models);
        }

        return parent::newCollection($query);
    }
}
