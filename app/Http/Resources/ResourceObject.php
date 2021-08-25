<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceObject extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'type' => $this->resource->getTable(),
            'id' => $this->resource->getRouteKey(),
            'attributes' => $this->resource->fields(),
            'links' => [
                'self' => route('api.v1.articles.show', $this->resource)
            ]
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->withHeaders([
            'Location' => route('api.v1.' . $this->resource->getTable() . '.show', $this->resource)
        ]);
    }
}
