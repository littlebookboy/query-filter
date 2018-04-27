<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostWhereInFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->in('category', $value);
    }
}
