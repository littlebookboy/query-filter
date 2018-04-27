<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostLessEqualsFilter extends QueryFilter
{
    public function category($value)
    {
        return $this->lessEquals('category', $value);
    }
}
