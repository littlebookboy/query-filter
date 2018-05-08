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
     * @param array|string $sortBy
     * @return Builder $builder
     */
    public function scopeSortBy($query, $sortBy)
    {
        if (is_string($sortBy)) {
            $sortBy = explode(',', $sortBy);
        }

        collect($sortBy)->mapWithKeys(function (string $field) {
            switch (substr($field, 0, 1)) {
                case '-':
                    return [substr($field, 1) => 'desc'];
                case ' ':
                    return [substr($field, 1) => 'asc'];
                default:
                    return [$field => 'asc'];
            }
        })->each(function (string $order, string $field) use ($query) {
            if (in_array($field, $this->sortable)) {
                $query->orderBy($field, $order);
                $this->addSorter($field, $order);
            }
        });

        return $query;
    }
}
