# Introduction
The `fet/laminas-rate-limiting` package provides a rate limiting solution for Laminas MVC applications. It allows you to set limits on how often certain routes can be accessed within a specified time window.

# Installation
To install this package, use Composer:

```bash
composer require fet/laminas-rate-limiting
```

> Make sure you have Composer installed on your system before running this command.

Enable the `Fet\LaminasRateLimiting` module in your `config/application.config.php` file.

```php
return [
    // ... other modules ...
    'Fet\\LaminasRateLimiting',
];
```

# Configuration
Define your rate limiting rules in your `module.config.php` or global configuration file.

```php
return [
    'rate_limiting' => [
        'max_requests' => 100, // max number of requests
        'window' => 60, // time window in seconds
        'routes' => [ // routes to apply rate limiting (wildcards allowed)
            'home',
            'api/*',
            'admin/*',
        ],
    ],
];
```

# Storages
As of the current release, this package exclusively supports **Redis** for backend storage.

```php
return [
    'rate_limiting' => [
        // ... other config options ...
        'storage' => [
            'options' => [
                'server' => '127.0.0.1',
                'port' => 6379
            ],
        ],
    ],
];
```

## Custom storage
If you want to provide your own storage implementation, make sure your class adheres to the `Fet\LaminasRateLimiting\Storage\StorageInterface` contract.

```php
use Acme\Storage\DatabaseStorage;

return [
    'rate_limiting' => [
        // ... other config options ...
        'storage' => [
            'name' => DatabaseStorage::class, // this will be loaded via service manager
            'options' => [],
        ],
    ],
];
```

# Tests
Run the tests with:

```bash
composer test
```