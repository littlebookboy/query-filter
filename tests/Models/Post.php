<?php

namespace Kblais\QueryFilter\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Kblais\QueryFilter\Traits\FilterableTrait;
use Kblais\QueryFilter\Traits\SortableTrait;

class Post extends Model
{
    use FilterableTrait;
    use SortableTrait;

    protected $fillable = ['title', 'content', 'category'];
}
