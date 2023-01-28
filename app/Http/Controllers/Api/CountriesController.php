<?php

namespace App\Http\Controllers\Api;

use App\Actions\CountriesIndexAction;
use App\Http\Controllers\Controller;

class CountriesController extends Controller 
{
    public function index(CountriesIndexAction $countriesIndexAction) 
    {
        return $countriesIndexAction();
    }
}