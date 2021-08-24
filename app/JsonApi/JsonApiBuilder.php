<?php

namespace App\JsonApi;

use Illuminate\Support\Str;

class JsonApiBuilder
{
    public function jsonPaginate()
    {
        return function () {
            return $this->paginate(
                $perPage = request('page.size'),
                $columns = ['*'],
                $pageName = 'page[number]',
                $page = request('page.number')
            )->appends(request()->except('data', 'page.number'));
        };
    }

    public function applySorts()
    {
        return function () {
            if (!property_exists($this->model, 'allowedSorts')) {
                abort(500, "Please set the public property allowed sorts inside " . get_class($this->model));
            }

            if (is_null($sort = request('sort'))) {
                return $this;
            }

            $sortFields = Str::of($sort)->explode(',');

            foreach ($sortFields as $sortField) {
                $direction = 'asc';
                if (Str::of($sortField)->startsWith('-')) {
                    $direction = 'desc';
                    $sortField = Str::of($sortField)->substr(1);
                }

                if (!collect($this->model->allowedSorts)->contains($sortField)) {
                    abort(400, "Invalid Query Parameter, {$sortField} is not allowed");
                }

                $this->orderBy($sortField, $direction);

                return $this;
            }
        };
    }

    public function applyFilters()
    {
        return function () {
            foreach (request('filter', []) as $filter => $value) {
                if ($filter === 'year') {
                    $this->whereYear('created_at', $value);
                } else if ($filter === 'month') {
                    $this->whereMonth('created_at', $value);
                } else {
                    $this->where($filter, 'LIKE', "%" . $value . "%");
                }
            }

            return $this;
        };
    }
}
