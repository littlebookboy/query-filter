<?php

namespace Kblais\QueryFilter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Kblais\QueryFilter\Traits\HasFilteringTrait;
use ReflectionMethod;
use ReflectionParameter;

abstract class QueryFilter
{
    use HasFilteringTrait;

    /**
     * The request object.
     *
     * @var Request
     */
    protected $request;

    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected $builder;

    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        if (empty($this->filters()) && method_exists($this, 'default')) {
            call_user_func([$this, 'default']);
        }

        foreach ($this->filters() as $name => $value) {
            $methodName = camel_case($name);
            if ($value !== 0 && $value !== '0') {
                $value = array_filter([$value]);
            } else {
                $value = [$value];
            }
            if ($this->shouldCall($methodName, $value)) {
                call_user_func_array([$this, $methodName], $value);
                $this->addFilter($methodName, $value);
            }
        }

        return $this->builder;
    }

    /**
     * Get all request filters data.
     *
     * @return array
     */
    protected function filters(): array
    {
        return $this->request->all();
    }

    /**
     * Make sure the method should be called
     *
     * @param string $methodName
     * @param array $value
     * @return bool
     */
    protected function shouldCall(string $methodName, array $value): bool
    {
        if (!method_exists($this, $methodName)) {
            return false;
        }
        $method = new ReflectionMethod($this, $methodName);
        /** @var ReflectionParameter $parameter */
        $parameter = Arr::first($method->getParameters());
        return $value ? $method->getNumberOfParameters() > 0 :
            $parameter === null || $parameter->isDefaultValueAvailable();
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            return call_user_func_array([$this->builder, $name], $arguments);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Extend Comparison
    |--------------------------------------------------------------------------
    */
    /**
     * Helper for "=" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function equals(string $column, $value): Builder
    {
        return $this->builder->where($column, '=', $value);
    }

    /**
     * Helper for "<>" or "!=" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function notEquals(string $column, $value): Builder
    {
        return $this->builder->where($column, '!=', $value);
    }

    /**
     * Helper for ">" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function greaterThan(string $column, $value): Builder
    {
        return $this->builder->where($column, '>', $value);
    }

    /**
     * Helper for ">=" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function greaterEquals(string$column, $value): Builder
    {
        return $this->builder->where($column, '>=', $value);
    }

    /**
     * Helper for "<" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function lessThan(string$column, $value): Builder
    {
        return $this->builder->where($column, '<', $value);
    }

    /**
     * Helper for "<=" filter
     *
     * @param  string $column
     * @param  string|number $value
     * @return Builder
     */
    protected function lessEquals(string $column, $value): Builder
    {
        return $this->builder->where($column, '<=', $value);
    }

    /**
     * Helper for "LIKE" filter
     *
     * @param  string $column
     * @param  string $value
     * @return Builder
     */
    protected function like(string $column, string $value): Builder
    {
        if ($this->builder->getQuery()->getConnection()->getDriverName() == 'pgsql') {
            return $this->builder->where($column, 'ILIKE', '%' . $value . '%');
        }

        return $this->builder->where($column, 'LIKE', '%' . $value . '%');
    }

    /**
     * Helper for Multi "LIKE" filter
     *
     * @param  string $column
     * @param  string $value
     * @return Builder
     */
    protected function multiLike(string $column, string $value): Builder
    {
        $words = array_filter(explode(' ', $value));

        if ($this->builder->getQuery()->getConnection()->getDriverName() == 'pgsql') {
            foreach ($words as $word) {
                $this->builder->where($column, 'ILIKE', '%' . $word . '%');
            }
            return $this->builder;
        }

        foreach ($words as $word) {
            $this->builder->where($column, 'LIKE', '%' . $word . '%');
        }
        return $this->builder;
    }

    /**
     * Helper for "include" filter
     *
     * @param  string $column
     * @param  array|collection $value
     * @return Builder
     */
    protected function in(string $column, $value): Builder
    {
        return $this->builder->whereIn($column, $value);
    }

    /**
     * Helper for "datetime between" filter
     *
     * @param string $column
     * @param Carbon|string $begin
     * @param Carbon|string $end
     * @return Builder
     */
    protected function dtBetween(string $column, $begin, $end): Builder
    {
        if (is_string($begin)) {
            $begin = Carbon::parse($begin);
        }

        if (is_string($end)) {
            $end = Carbon::parse($end);
        }

        $this->builder->where($column, '>=', $begin->toDateTimestring());
        $this->builder->where($column, '<=', $end->toDateTimestring());

        return $this->builder;
    }
}
