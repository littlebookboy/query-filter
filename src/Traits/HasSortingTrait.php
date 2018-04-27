<?php

namespace Kblais\QueryFilter\Traits;

/**
 * Trait HasSortingTrait
 * @package Kblais\QueryFilter\Traits
 * @aim 設定、取得已套用的過濾條件 Key-Value
 */
trait HasSortingTrait
{
    /**
     * The sort by information.
     *
     * @var array
     */
    protected $sortBy;

    /*
    |--------------------------------------------------------------------------
    | 設定、取得已套用的過濾條件 Key-Value
    |--------------------------------------------------------------------------
    */
    /**
     * add a filter to condition array
     * @param $sorter
     * @param $value
     */
    public function addSorter($sorter, $value)
    {
        $this->sortBy[$sorter] = $value;
    }

    /**
     * get the filter condition array
     */
    public function getSorter()
    {
        return $this->sortBy;
    }
}
