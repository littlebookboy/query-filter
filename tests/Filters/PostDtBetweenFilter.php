<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostDtBetweenFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->dtBetween('category', array_get($value, 'begin'), array_get($value, 'end'));
    }
}
