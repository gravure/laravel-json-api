# Laravel JSON api

[![codecov](https://codecov.io/gh/gravure/laravel-json-api/branch/master/graph/badge.svg)](https://codecov.io/gh/gravure/laravel-json-api)

This package assists at generating [JSON API v1](http://jsonapi.org/) compatible output.

## Installation

```bash
composer require gravure/laravel-json-api
```

Register the service provider in your `config/app.php` under `providers`:

```php
    Gravure\Api\Providers\ApiProvider::class,
```

## Usage

An important element of this package is the `ReplacesRequest` middleware that is automatically
active for any request that accepts `application/vnd.api+json` content. The inject Request
object provides a set of helper subclasses, for instance for handling pagination or filtering.

Aside from that, by extending the abstract ResourceController or Controller one can easily
generate Json documents based on the specification. The ResourceController in fact provides a
complete Laravel ResourceController implementation, where it only needs a Repository to process
database specific logic.
