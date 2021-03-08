<?php

namespace FilippoToso\Domain\Traits;

trait QueryBuildable
{
    /**
     * Automagically link the existing Query Builder class
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        $tokens = explode('\\', get_class($this));
        $model = last($tokens);
        $tokens = array_slice($tokens, 0, length($tokens) - 2);
        $queryBuilderClass = implode('\\', $tokens) . '\\QueryBuilders\\' . $model . 'QueryBuilder';

        if (class_exists($queryBuilderClass)) {
            return new $queryBuilderClass($query);
        }

        return parent::newEloquentBuilder($query);
    }
}
