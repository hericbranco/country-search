<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
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

    private function getCountries(array $data): Collection
    {
        ksort($data);
        $stringKey = serialize($data);
        $cached = Redis::get($stringKey);
        if ($cached) {
            echo 'cached';
            return collect(unserialize($cached));
        }

        $http = Http::get('https://api.first.org/data/v1/countries', $data);
        Redis::set($stringKey, serialize($http->object()->data));
        return collect($http->object()->data);
    }

    public function __invoke(array $data)
    {
        $this->validateData($data);
        $data = array_merge($data, ['offset' => 0]);
        if (!$data['limit']) {
            $data['limit'] = config('custom.ItemsPerPage');
        }
        if ($data['page']) {
            $data['offset'] = ($data['page'] - 1) * $data['limit'];
        }

        return $this->getCountries($data);
    }
}
