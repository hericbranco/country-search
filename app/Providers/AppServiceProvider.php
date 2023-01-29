<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Macro to transform default collection in paginated collection to use 
         * ->links wit default Laravel call
         */
        if (!Collection::hasMacro('transformToPaginatedCollection')) {
            Collection::macro(
                'transformToPaginatedCollection',
                function ($perPage = null, $page = null, $options = []) {
                    $perPage = $perPage??config('custom.ItemsPerPage');
                    $actualPage = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    $queryString = array_map(function ($value, $key) {
                        return $key . '=' . $value;
                    }, request()->except('page'), array_keys(request()->except('page')));
                    $this->path = request()->url() . '?';
                    if (!empty($queryString)) {
                        $this->path = $this->path . implode('&', $queryString) . '&';
                    }
            
                    $this->prev_page_url = ($actualPage > 1) ? $this->path . 'page=' . ($actualPage - 1) : '';
                    $this->next_page_url = ($this->count() == $perPage) ? $this->path . 'page=' . ($actualPage + 1) : '';
            
                    $this->macro('links', function () {
                        return view('includes.pagination', ['data' => $this, 'show_page_links' => false]);
                    });
                    return $this;
                }
            );
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
