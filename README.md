Migration Toolkit: Creating ORM Compatible Structure from Legacy Database
=====================

[![Join the chat at https://gitter.im/orchestral/platform/components](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/orchestral/platform/components?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

Have you ever taken a project that had a messy, unstructured database design? Have you ever wish you can transform those project to become more Eloquent friendly?

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/transporter.svg?style=flat-square)](https://packagist.org/packages/orchestra/transporter)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/transporter.svg?style=flat-square)](https://packagist.org/packages/orchestra/transporter)
[![MIT License](https://img.shields.io/packagist/l/orchestra/transporter.svg?style=flat-square)](https://packagist.org/packages/orchestra/transporter)
[![Build Status](https://img.shields.io/travis/orchestral/transporter/3.2.svg?style=flat-square)](https://travis-ci.org/orchestral/transporter)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/transporter/3.2.svg?style=flat-square)](https://coveralls.io/r/orchestral/transporter?branch=3.2)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/transporter/3.2.svg?style=flat-square)](https://scrutinizer-ci.com/g/orchestral/transporter/)

```php
use App\User;
use Illuminate\Database\Query\Builder;
use Orchestra\Transporter\Blueprint;
use Orchestra\Transporter\Schema;

Schema::table('member', function (Blueprint $blueprint) {
    $blueprint->connection('legacy')
        ->key('member_id')
        ->filter(function (Builder $query) {
            $query->where('active', '=', 1);
        })->transport(function ($data) {
            return new User([
                'email' => $data->u_email,
                'password' => $data->u_password,
            ]);
        });
})->start();
```

* [Version Compatibility](#version-compatibility)
* [Installation](#installation)

## Version Compatibility

Laravel  | Transporter
:--------|:---------
 5.1.x   | 3.1.x
 5.2.x   | 3.2.x

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require": {
        "orchestra/transporter": "~3.1"
    }
}
```

And then run `composer install` to fetch the package.

### Quick Installation

You could also simplify the above code by using the following command:

    composer require "orchestra/transporter=~3.1"

### Setup

For simplicity, Transporter Component doesn't include any service provider. You can simply run the migration via:

    php artisan migrate --path=vendor/orchestra/transporter/resources/database/migrations

