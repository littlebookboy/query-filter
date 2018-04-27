<?php

namespace Kblais\QueryFilter\Traits;

/**
 * Trait HasFilteringTrait
 * @package Kblais\QueryFilter\Traits
 * @aim 設定、取得已套用的過濾條件 Key-Value
 */
trait HasFilteringTrait
{
    /**
     * The filter by information.
     *
     * @var array
     */
    protected $filterBy;

    /*
    |--------------------------------------------------------------------------
    | 設定、取得已套用的過濾條件 Key-Value
    |--------------------------------------------------------------------------
    */
    /**
     * add a filter to condition array
     * @param string $filter 欄位名稱
     * @param array|string|number $value 過濾條件
     */
    public function addFilter(string $filter, $value)
    {
        $this->filterBy[$filter] = $value;
    }

    /**
     * get the filter condition array
     * @param $filter
     * @param $value
     */
    public function getFilter()
    {
        return $this->filterBy;
    }
}
