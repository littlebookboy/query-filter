<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostGreaterEqualsFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->greaterEquals('category', $value);
    }
}
