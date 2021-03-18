<?php

namespace FilippoToso\Domain\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;

class ResourceCollection extends BaseResourceCollection
{

    protected $resource_class = Resource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => call_user_func([$this->resource_class, 'collection'], $this->collection),
        ];
    }

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($resource, $class = null)
    {
        parent::__construct($resource);
        $this->resource_class = $class;
    }
}
