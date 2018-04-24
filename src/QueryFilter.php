<?php

namespace Kblais\QueryFilter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ReflectionMethod;
use ReflectionParameter;

abstract class QueryFilter
{
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
     * Hydrate the filters from plain array.
     *
     * @author Andrea Marco Sartori, source https://github.com/cerbero90/query-filters
     * @param    array $queries
     * @return    static
     */
    public static function hydrate(array $queries)
    {
        $request = new Request($queries);
        return new static($request);
    }

    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        if (empty($this->filters()) && method_exists($this, 'default')) {
            call_user_func([$this, 'default']);
        }

        foreach ($this->filters() as $name => $value) {
            $methodName = camel_case($name);
            $value = array_filter([$value]);
            if ($this->shouldCall($methodName, $value)) {
                call_user_func_array([$this, $methodName], $value);
            }
        }

        return $this->builder;
    }

    /**
     * Get all request filters data.
     *
     * @return array
     */
    protected function filters()
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
    protected function shouldCall($methodName, array $value)
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
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function equals($column, $value)
    {
        return $this->builder->where($column, '=', $value);
    }

    /**
     * Helper for "<>" or "!=" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function notEquals($column, $value)
    {
        return $this->builder->where($column, '!=', $value);
    }

    /**
     * Helper for ">" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function greaterThan($column, $value)
    {
        return $this->builder->where($column, '>', $value);
    }

    /**
     * Helper for ">=" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function greaterEquals($column, $value)
    {
        return $this->builder->where($column, '>=', $value);
    }

    /**
     * Helper for "<" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function lessThan($column, $value)
    {
        return $this->builder->where($column, '<', $value);
    }

    /**
     * Helper for "<=" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function lessEquals($column, $value)
    {
        return $this->builder->where($column, '<=', $value);
    }

    /**
     * Helper for "LIKE" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function like($column, $value)
    {
        if ($this->builder->getQuery()->getConnection()->getDriverName() == 'pgsql') {
            return $this->builder->where($column, 'ILIKE', '%' . $value . '%');
        }

        return $this->builder->where($column, 'LIKE', '%' . $value . '%');
    }

    /**
     * Helper for "include" filter
     *
     * @param  String $column
     * @param  String $value
     * @return Builder
     */
    protected function in($column, $value)
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
    protected function dtBetween($column, $begin, $end)
    {
        if (is_string($begin)) {
            $begin = Carbon::parse($begin);
        }

        if (is_string($end)) {
            $end = Carbon::parse($end);
        }

        return $this->builder->where(function($qb) use ($column, $begin, $end) {

            if ($begin->gt($end)) {
                $temp = $begin;
                $begin = $end;
                $end = $temp;
            }

            $qb->where($column, '>=', $begin->toDateTimeString());
            $qb->where($column, '<=', $end->toDateTimeString());
        });
    }
}
