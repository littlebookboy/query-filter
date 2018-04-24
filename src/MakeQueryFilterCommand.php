<?php

namespace Kblais\QueryFilter;

use Illuminate\Console\GeneratorCommand;

/**
 * Artisan command to create query filters.
 *
 * @author Andrea Marco Sartori, source https://github.com/cerbero90/query-filters
 */
class MakeQueryFilterCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:query-filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new query filter class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Query filter';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/query_filter.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($path = config('query_filter.path')) {
            return $rootNamespace . '\\' . str_replace('/', '\\', $path);
        }

        return $rootNamespace;
    }
}