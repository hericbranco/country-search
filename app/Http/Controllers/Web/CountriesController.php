<?php

namespace App\Http\Controllers\Web;

use App\Actions\CountriesIndexAction;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller
{
    private function transformToPaginatedCollection(Collection $collection, Request $request)
    {
        $actualPage = request('page') ?? 1;
        $limit = request('limit') ?? 15;
        $queryString = array_map(function ($value, $key) {
            return $key . '=' . $value;
        }, request()->except('page'), array_keys(request()->except('page')));
        $collection->path = request()->url() . '?';
        if (!empty($queryString)) {
            $collection->path = $collection->path . implode('&', $queryString) . '&';
        }

        $collection->prev_page_url = ($actualPage > 1) ? $collection->path . 'page=' . ($actualPage - 1) : '';
        $collection->next_page_url = ($collection->count() == $limit) ? $collection->path . 'page=' . ($actualPage + 1) : '';

        $collection->macro('links', function () {
            return view('includes.pagination', ['data' => $this, 'show_page_links' => false]);
        });
        return $collection;
    }

    private function getCountries(CountriesIndexAction $countriesIndexAction, Request $request)
    {
        try {
            $countries = $countriesIndexAction(request()->all(['q', 'region', 'page', 'limit']));
            return responseOk('', $this->transformToPaginatedCollection($countries, $request));
        } catch (ValidationException $v) {
            return responseFail($v->getMessage(), $v->errorBag->all());
        } catch (Throwable $t) {
            Log::critical($t->getMessage());
            return responseFail($t->getMessage(), []);
        }
    }

    public function index(CountriesIndexAction $countriesIndexAction)
    {
        return view('countries.index', ['countries' => $this->getCountries($countriesIndexAction, request())]);
    }
}
