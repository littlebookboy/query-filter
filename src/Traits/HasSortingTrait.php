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
     * @param string $field 欄位名稱
     * @param string $order 排序條件
     */
    public function addSorter(string $field, string $order)
    {
        $this->sortBy[$field] = $order;
    }

    /**
     * get the filter condition array
     */
    public function getSorter()
    {
        return $this->sortBy;
    }
}
