<?php

namespace App\Actions;

use Facade\FlareClient\Http\Exceptions\NotFound;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;


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
        if (Redis::exists($stringKey)) {
            Log::debug('Use cache to return this data', ['key' => $stringKey]);
            return collect(unserialize(Redis::get($stringKey)));
        }

        $http = Http::get(config('custom.CountriesApiUrl'), $data);
        if (data_get($http->object(), 'status-code') != 200) {
            throw new NotFound('Content not found', 404);
        }
        $countries = data_get($http->object(), 'data');
        Redis::setex($stringKey, config('custom.RedisCacheTime'), serialize($countries));
        Log::debug('Use Api to return this data, save cache', ['key' => $stringKey]);
        return collect($countries);
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
