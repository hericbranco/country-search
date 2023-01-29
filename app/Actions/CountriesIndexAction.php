<?php

namespace App\Actions;

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

        $http = Http::get('https://api.first.org/data/v1/countries', $data);
        Redis::setex($stringKey, config('custom.RedisCacheTime'), serialize($http->object()->data));
        Log::debug('Use Api to return this data, save cache', ['key' => $stringKey]);
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
