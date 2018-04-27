<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostNotEqualsFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->notEquals('category', $value);
    }
}
