<?php

namespace Kblais\QueryFilter\Traits;

use Illuminate\Database\Eloquent\Builder;
use Kblais\QueryFilter\QueryFilter;

trait FilterableTrait
{
    /**
     * Filter a result set.
     *
     * @param  Builder      $query
     * @param  QueryFilter $filters
     * @return Builder
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
