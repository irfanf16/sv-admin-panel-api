<?php

namespace App\NestableCollection;

trait NestableTrait
{
    /**
     * Return a custom nested collection.
     *
     * @return NestableCollection
     */
    public function newCollection(array $models = [])
    {
        return new NestableCollection($models);
    }
}
