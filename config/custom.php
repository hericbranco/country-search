<?php
return [
    'ItemsPerPage' => env('ITEMS_PER_PAGE', 15),
    'RedisCacheTime' => env('REDIS_CACHE_TIME', 108000),
    'CountriesApiUrl' => env('COUNTRIES_API_URL', 'https://api.first.org/data/v1/countries'),
    'FlagsImgUrl' => env('FLAGS_IMG_URL', 'FLAGS_IMG_URL=https://countryflagsapi.com/png')
];