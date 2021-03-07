<?php

namespace FilippoToso\Domain\Traits;

trait Domainable
{
    public function newEloquentBuilder($query)
    {
        $class = get_class($this);
        $tokens = explode('\\', $class);
        $tokens = array_slice($tokens, 0, length($tokens) - 2);
        $queryBuilderClass = implode('\\', $tokens) . '\\QueryBuilders\\' . $class . 'QueryBuilder';

        if (class_exists($queryBuilderClass)) {
            return new $queryBuilderClass($query);
        }

        return parent::newEloquentBuilder($query);
    }

    public function newCollection(array $models = [])
    {
        $class = get_class($this);
        $tokens = explode('\\', $class);
        $tokens = array_slice($tokens, 0, length($tokens) - 2);
        $queryBuilderClass = implode('\\', $tokens) . '\\Collections\\' . $class . 'Collection';

        if (class_exists($queryBuilderClass)) {
            return new $queryBuilderClass($query);
        }

        return parent::newCollection($query);
    }
}
