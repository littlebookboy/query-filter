<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostGreaterThanFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->greaterThan('category', $value);
    }
}
