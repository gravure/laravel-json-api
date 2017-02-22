<?php

namespace Gravure\Api\Tests\Serializers;

use Gravure\Api\Contracts\Serializer;
use Illuminate\Support\Arr;

class DummySerializer implements Serializer
{

    /**
     * Get the type.
     *
     * @param mixed $model
     * @return string
     */
    public function getType($model)
    {
        return 'dummies';
    }

    /**
     * Get the id.
     *
     * @param mixed $model
     * @return string
     */
    public function getId($model)
    {
        return $model->id;
    }

    /**
     * Get the attributes array.
     *
     * @param mixed $model
     * @param array|null $fields
     * @return array
     */
    public function getAttributes($model, array $fields = null)
    {
        $values = $model->getAttributes();

        return $fields ? Arr::only($values, $fields) : $values;
    }

    /**
     * Get the links array.
     *
     * @param mixed $model
     * @return array
     */
    public function getLinks($model)
    {
        return [];
    }

    /**
     * Get the meta.
     *
     * @param mixed $model
     * @return array
     */
    public function getMeta($model)
    {
        return [];
    }

    /**
     * Get a relationship.
     *
     * @param mixed $model
     * @param string $name
     * @return \Tobscure\JsonApi\Relationship|null
     */
    public function getRelationship($model, $name)
    {
        return [];
    }
}
