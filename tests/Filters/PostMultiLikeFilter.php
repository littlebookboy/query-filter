<?php

namespace Kblais\QueryFilter\Tests\Filters;

use Kblais\QueryFilter\QueryFilter;

class PostMultiLikeFilter extends QueryFilter
{
    public function content($value)
    {
        return $this->multiLike('content', $value);
    }
}
