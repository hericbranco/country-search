<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Http\Client\Response;
use Throwable;
use Illuminate\Support\Facades\Redis;


class CountriesIndexAction
{
    private function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'region' => ['nullable', 'string', 'min:3'],
            'q' => ['nullable', 'string', 'min:2'],
            'page' => ['nullable', 'numeric']
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors());
        }
    }

    private function getCountries(array $data) :Collection
    {
        $keys = array_keys($data);
        sort($keys);
        $stringKey = [];
        array_walk($keys, function($key) use (&$stringKey, $data) {
            $stringKey[] = $key.':'.$data[$key];
        });
        $stringKey = implode('|', $stringKey);
        $cached = Redis::get($stringKey);
        if ($cached) {
            echo 'cached';
            return collect(json_decode($cached));
        }

        $http = Http::get('https://api.first.org/data/v1/countries', $data);
        Redis::set($stringKey, json_encode($http->object()->data));
        return collect($http->object()->data);
    }

    public function __invoke(array $data)
    {
        try {
            $this->validateData($data);
            $data = array_merge($data, ['offset' => 0]);
            if (!$data['limit']) {
                $data['limit'] = 15;
            }
            $actualPage=1;
            if ($data['page']) {
                $actualPage = $data['page'];
                $data['offset'] = ($data['page']-1)*$data['limit'];
            }

            $countries = $this->getCountries($data);
            $queryString = array_map(function ($value, $key) {
                return $key . '=' . $value;
            }, request()->except('page'), array_keys(request()->except('page')));
            $countries->path = request()->url() . '?';
            if (!empty($queryString)) {
                $countries->path = $countries->path . implode('&', $queryString) . '&';
            }
            
            $countries->prev_page_url = ($actualPage > 1)?$countries->path.'page='.($actualPage-1):'';
            $countries->next_page_url = ($countries->count() == $data['limit'])?$countries->path.'page='.($actualPage+1):'';

            $countries->macro('links', function () {
                return view('includes.pagination', ['data' => $this, 'show_page_links' => false]);
            });
            return responseOK('', $countries);
        } catch (ValidationException $v) {
            return responseFail($v->getMessage(), $v->errorBag->all());
        } catch (Throwable $t) {
            Log::critical($t->getMessage());
            return responseFail($t->getMessage(), []);
        }
    }
}
