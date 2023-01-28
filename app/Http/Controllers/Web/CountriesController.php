<?php

namespace App\Http\Controllers\Web;

use App\Actions\CountriesIndexAction;
use App\Http\Controllers\Controller;

class CountriesController extends Controller 
{
    public function index(CountriesIndexAction $countriesIndexAction) 
    {
        $countries = $countriesIndexAction(request()->all());
        return view('countries.index', ['countries' => $countries]);
    }
}