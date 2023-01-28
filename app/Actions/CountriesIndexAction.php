<?php

namespace App\Actions;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class CountriesIndexAction 
{
    private function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'region' => ['string'],
            'q' => ['string']
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator, null, $validator->errors());
        }
    }

    public function __invoke(array $data)
    {
        try {
            $this->validateData($data);
            $http = Http::get('https://api.first.org/data/v1/countries', $data);
            return responseOK('', (array) $http->object()->data);
        } catch (ValidationException $v) {
            return responseFail($v->getMessage(), $v->errorBag->all());
        } catch (Throwable $t) {
            return responseFail($t->getMessage(), []);
        }
    }
}