<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
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
                function (Request $request, $perPage = null, $page = null) {
                    $perPage = $perPage??config('custom.ItemsPerPage');
                    $actualPage = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    $queryString = array_map(function ($value, $key) {
                        return $key . '=' . $value;
                    }, $request->except('page'), array_keys($request->except('page')));

                    $newCollect = collect(['data' => $this]);
                    
                    $path = $request->url() . '?';
                    if (!empty($queryString)) {
                        $path = $path.implode('&', $queryString).'&';
                    }
                    data_set($newCollect, 'path', $path);
                    data_set($newCollect, 'prev_page_url', ($actualPage > 1) ? $path . 'page=' . ($actualPage - 1) : '');
                    data_set($newCollect, 'next_page_url', (count(data_get($newCollect, 'data')) == $perPage) ? $path . 'page=' . ($actualPage + 1) : '');
            
                    $newCollect->macro('links', function () {
                        return view('includes.pagination', ['data' => $this, 'show_page_links' => false]);
                    });
                    return $newCollect;
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
