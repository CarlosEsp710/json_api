<?php

namespace App\JsonApi\Articles;

use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'articles';

    /**
     * @param \App\Models\Article $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param \App\Models\Article $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'title' => $resource->title,
            'slug' => $resource->slug,
            'content' => $resource->content,
            'createdAt' => $resource->created_at,
            'updatedAt' => $resource->updated_at,
        ];
    }

    public function getRelationships($article, $isPrimary, array $includeRelationships)
    {
        return [
            'authors' => [
                'related' => true,
                'showSelf' => true,
                'showData' => isset($includeRelationships['authors']),
                'data' => function () use ($article) {
                    return $article->user;
                }
            ]
        ];
    }
}
