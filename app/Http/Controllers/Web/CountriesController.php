<?php

namespace App\Http\Controllers\Web;

use App\Actions\CountriesIndexAction;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller
{
    private function getCountries(CountriesIndexAction $countriesIndexAction, Request $request)
    {
        try {
            $countries = $countriesIndexAction(request()->all(['q', 'region', 'page', 'limit']));
            $perPage = request('limit')??config('custom.ItemsPerPage');
            return responseOk('', $countries->transformToPaginatedCollection($perPage));
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
