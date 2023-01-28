# laravel-typeable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kiwilan/laravel-typeable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typeable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typeable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/kiwilan/laravel-typeable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/kiwilan/laravel-typeable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/kiwilan/laravel-typeable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/kiwilan/laravel-typeable.svg?style=flat-square)](https://packagist.org/packages/kiwilan/laravel-typeable)

Laravel package to types Eloquent models with full TypeScript type.

## Installation

You can install the package via composer:

```bash
composer require kiwilan/laravel-typeable
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-typeable-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Features

-   [x] Generate TypeScript types for Eloquent models
-   [x] Generate TypeScript types for Eloquent relations
-   [x] Generate TypeScript types for `casts` (include native `enum` support)
-   [x] Generate TypeScript types for `dates`
-   [x] Generate TypeScript types for `appends` (partial for Attribute Casting)

## Usage

```bash
php artisan typeable:models
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Kiwilan](https://github.com/kiwilan)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
