# Simple Customer Importer

## Getting started

1. in your terminal: `composer install`
2. create the .env: `cp .env.example .env`
3. create the sqlite db: `touch database/database.sqlite`
4. generate app key: `php artisan key:gen`
5. run the migrations: `php artisan migrate`

## Executing Users Importer

in your terminal: `php artisan app:import-users`

optional args: --results --nationality

ex: `php artisan app:import-users --results=1000 --nationality=US`

## API Endpoints
- GET: api/customers
  - returns all customers
- GET: api/customers/{customerId}
  - returns a customer by id

## Running Unit Test
1. in your terminal, create the testing db: `touch database/database.testing.sqlite`
2. run unit test: `php artisan test`
