<?php

namespace FilippoToso\Domain\Resources;

use Illuminate\Http\Resources\Json\JsonResource as BaseResource;
use Illuminate\Database\Eloquent\Collection;

class Resource extends BaseResource
{
    protected function include($relationship, $resourceClass, $default = [], $flat = false)
    {
        if ($this->relationLoaded($relationship)) {
            if (is_a($this->$relationship, Collection::class)) {
                if ($flat) {
                    return new $resourceClass($this->$relationship->first());
                }
                return new UnwrappedResourceCollection($this->$relationship, $resourceClass);
            }
            return new $resourceClass($this->$relationship);
        }

        return $default;
    }
}
