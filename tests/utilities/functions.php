<?php

/**
 * Create a Eloquent Model
 * for the given class
 * @param $class, @param $attributes
 */
function create($class, $attributes = [])
{
    return factory($class)->create($attributes);
}

/**
 * Create a Eloquent Model
 * for the given class but do not persist it
 * @param $class, @param $attributes
 */
function make($class, $attributes = [])
{
    return factory($class)->make($attributes);
}

