<?php

namespace Kblais\QueryFilter;

use Illuminate\Support\ServiceProvider;

/**
 * The query filters service provider.
 *
 * @author Andrea Marco Sartori, source https://github.com/cerbero90/query-filters
 */
class QueryFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/query_filter.php', 'query_filter');

        if ($this->app->runningInConsole()) {
            $this->publish();
            $this->commands(MakeQueryFiltersCommand::class);
        }
    }

    /**
     * publish
     */
    public function publish()
    {
        $this->publishes([
            __DIR__ . '/../config/query_filter.php' => config_path('query_filter.php'),
        ], 'query_filter_config');
    }
}
