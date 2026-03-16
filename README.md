# Codepreneur HalkPay Package

Reusable Laravel package for HalkPay 3D Hosted gateway integrations.

## Installation

```bash
composer require codepreneur/halk-pay
```

> The package supports Laravel 11/12 and PHP 8.2+.

## Setup (works in any Laravel project)

### 1) Publish package config

```bash
php artisan vendor:publish --tag=halkpay-config
```

This creates `config/halkpay.php` in your app.

### 2) Configure environment variables

Add these to your `.env` file (values provided by HalkPay/bank):

```dotenv
HALKPAY_CLIENT_ID=
HALKPAY_STORE_KEY=
HALKPAY_OK_URL=
HALKPAY_FAIL_URL=
HALKPAY_CALLBACK_URL=
HALKPAY_GATEWAY_URL=https://entegrasyon.asseco-see.com.tr/fim/est3Dgate
HALKPAY_GATEWAY_METHOD=POST
HALKPAY_CURRENCY=934
HALKPAY_LANG=tk
HALKPAY_TRANSACTION_TYPE=Auth
```

### 3) Adjust project route behavior (optional)

In `config/halkpay.php`:

- Toggle package routes with `routes.enabled`
- Change route prefix via `routes.prefix`
- Add your middleware in `routes.middleware`
- Rename route names via `routes.name_prefix`

Default routes:

- `POST /halkpay/redirect` (`halkpay.redirect`)
- `POST /halkpay/callback` (`halkpay.callback`)

### 4) Trigger payment redirect from your app

Post to the redirect route from any controller/form:

```php
return redirect()->route('halkpay.redirect');
```

Typical payload:

- `amount` (required)
- `oid` (optional, generated if omitted)
- `customer_name` / `bill_to_name` (optional)
- `success_url` / `fail_url` / `callback_url` (optional overrides)
- `installment` (optional)

## Universal integration points

This package is designed to run in any project because domain-specific logic is optional and pluggable.

### Resolve your own OID format/reference (optional)

```php
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;

$this->app->bind(TransactionReferenceResolver::class, YourOidResolver::class);
```

### Process callback inside your own domain (optional)

```php
use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;

$this->app->bind(CallbackProcessor::class, YourCallbackProcessor::class);
```

If these contracts are not bound, the package safely falls back to no-op defaults.

## Callback handling and security

- Callback hash validation is built in (`HASHPARAMS`, `HASHPARAMSVAL`, `HASH` + `store_key`)
- Middleware alias: `halkpay.hash`
- Callback response mode is configurable: `callback.response_mode = json|redirect`

## Events

- `Codepreneur\HalkPayGateway\Events\HalkPayCallbackReceived`

Use this event for event-driven integration if preferred.
