<?php

namespace App\Http\Controllers\Api;

use App\Actions\CountriesIndexAction;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Support\Facades\Log;

class CountriesController extends Controller 
{
    public function index(CountriesIndexAction $countriesIndexAction) 
    {
        try {
            $countries = $countriesIndexAction(request()->all(['q', 'region', 'page', 'limit']));
            $perPage = request('limit')??config('custom.ItemsPerPage');
            // dd($countries->transformToPaginatedCollection(request(), $perPage));
            return responseOk('', $countries->transformToPaginatedCollection(request(), $perPage));
        } catch (ValidationException $v) {
            return responseFail($v->getMessage(), $v->errorBag->all());
        } catch (Throwable $t) {
            Log::critical($t->getMessage());
            return responseFail($t->getMessage(), []);
        }
    }
}