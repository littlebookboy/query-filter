<?php

namespace Kblais\QueryFilter\Traits;

use Illuminate\Database\Eloquent\Builder;

trait SortableTrait
{
    use HasSortingTrait;

    /**
     * 定義可被過濾的欄位
     * @var array
     */
    protected $sortable = [];

    /**
     * Sort the collection by the sort field
     * Examples:
     *      array => ['title', '-created_at', 'updated_at']
     *      string sort= title,-created_at,updated_at
     *
     * @source https://blog.jgrossi.com/2018/queryfilter-a-model-filtering-concept/
     * @param Builder $query
     * @param array|string $value
     * @return Builder $builder
     */
    protected function scopeSortBy($query, $value)
    {
        if (is_string($value)) {
            $value = explode(',', $value);
        }

        collect($value)->mapWithKeys(function (string $field) {
            switch (substr($field, 0, 1)) {
                case '-':
                    return [substr($field, 1) => 'desc'];
                case ' ':
                    return [substr($field, 1) => 'asc'];
                default:
                    return [$field => 'asc'];
            }
        })->each(function (string $field, string $order) use ($query, $sortable) {
            if (in_array($field, $sortable)) {
                $query->orderBy($field, $order);
                $this->addSorter($field, $order);
            }
        });

        return $query;
    }
}
