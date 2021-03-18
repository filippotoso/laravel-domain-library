<?php

namespace FilippoToso\Domain\Resources;

class UnwrappedResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return call_user_func([$this->resource_class, 'collection'], $this->collection);
    }
}
