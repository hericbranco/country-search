# CountriesListSearch
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/laravel-%23FF2D20.svg?style=for-the-badge&logo=laravel&logoColor=white)
![Redis](https://img.shields.io/badge/redis-%23DD0031.svg?style=for-the-badge&logo=redis&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)

CountriesListSearch is a PHP(Laravel) project to show and filter countries and their flags.

The project uses Redis to cache http requests to avoid slowdowns in country api calls

## Installation

After clone this project enter in created folder and execute composer install

```bash
cd <folder>
docker-compose up -d --build
cp .env.example .env
docker-compose exec php bash -c "composer install"
```

## Usage

After execute instalation commands [Click here](http://country-search.localhost) to view project.

The project cotains 2 routes for show/filter countries

 - [http://country-search.localhost](http://country-search.localhost)
 - [http://country-search.localhost/api/countries](http://country-search.localhost/api/countries)

## Credits

This project use two api's to show results(one for countries-list and search and another to show their flags)

 - [https://api.first.org/v1/get-countries](https://api.first.org/v1/get-countries)
 - [https://countryflagsapi.com](https://countryflagsapi.com)