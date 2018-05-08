<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filter
{
    protected $builder;
    protected $req;
    protected $filters = [];

    public function __construct(Request $req)
    {
        $this->req = $req;
    }

    /**
     * Loop through every string in
     * the filters array (aka all the query string keys).
     * If the calling class
     * has a method matching the filters name
     * it then calls that method passing in the
     * associated query string value from the request
     * as a parameter to that method.
     *
     * @param mixed $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    /**
     * Return all Query String key: value
     * pairs from the request that
     * correspond to method names from the filters array
     * @return array
     */
    protected function getFilters()
    {
        return $this->req->only($this->filters);
    }
}
