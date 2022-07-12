[![Packagist](https://img.shields.io/packagist/v/lazzard/psr7-response-sender?include_prereleases)](https://packagist.org/packages/lazzard/psr7-response-sender)
[![PHP version](https://img.shields.io/packagist/php-v/lazzard/psr7-response-sender)](https://packagist.org/packages/lazzard/psr7-response-sende)
[![tests](https://github.com/lazzard/psr7-response-sender/actions/workflows/tests.yml/badge.svg)](https://github.com/lazzard/ftp-bridge/actions/workflows/tests.yml)
[![CodeFactor](https://www.codefactor.io/repository/github/lazzard/psr7-response-sender/badge)](https://www.codefactor.io/repository/github/lazzard/psr7-response-sender)
[![codecov](https://codecov.io/gh/lazzard/psr7-response-sender/branch/main/graph/badge.svg?token=Q5TSCW01B8)](https://codecov.io/gh/lazzard/psr7-response-sender)
![License](https://img.shields.io/packagist/l/lazzard/php-ftp-client)

# Lazzard/Psr7ResponseSender

Simple PSR-7 compatible response sender.

```
composer require lazzard/psr7-response-sender
```

```php
<?php

use GuzzleHttp\Psr7\Response;
use Lazzard\Psr7ResponseSender\Sender;

$response = new Response;
$sender = new Sender;

$sender->send($response);
// OR
$sender($response);
```

## Testing

Run the PHPUnit tests :

```
vendor/bin/phpunit
```

## Sponsors

Special thanks to our supporters :

<div style="display:flex; align-items:center; justify-content: space-between;">
  <img width="150px" src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_square.png"/>
</div>

## Licence

MIT License. please see the [LICENSE FILE](LICENSE) for more information.
