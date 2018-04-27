<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostLessThanFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->lessThan('category', $value);
    }
}
