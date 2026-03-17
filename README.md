# Codepreneur HalkPay Package

Reusable Laravel package for HalkPay 3D Hosted gateway integrations.

## Installation

```bash
composer require codepreneur/halk-pay
```

> Supports Laravel 11/12 and PHP 8.2+.

## Package development structure

This package now follows the standard Spatie Laravel package skeleton conventions:

- Service provider built on `spatie/laravel-package-tools`
- Test suite powered by Pest + Orchestra Testbench
- CI workflow matrix for PHP/Laravel combinations
- Static analysis (`phpstan`) and formatting (`pint`) scripts

Run local quality checks:

```bash
composer test
composer analyse
composer format
```

## Setup (works in any Laravel project)

### 1) Publish package config

```bash
php artisan vendor:publish --tag=halkpay-config
```

### 2) Configure `.env`

```dotenv
HALKPAY_CLIENT_ID=
HALKPAY_STORE_KEY=
HALKPAY_STORE_TYPE=3D_PAY_HOSTING
HALKPAY_CURRENCY=807
HALKPAY_TRANSACTION_TYPE=Auth
HALKPAY_LANG=mk
HALKPAY_SUCCESS_URL=https://your-app.test/payments/success
HALKPAY_FAIL_URL=https://your-app.test/payments/fail
HALKPAY_GATEWAY_URL=https://torus-stage-halkbankmacedonia.asseco-see.com.tr/fim/est3Dgate

# Optional callback response behavior
HALKPAY_CALLBACK_RESPONSE_MODE=json
HALKPAY_CALLBACK_SUCCESS_REDIRECT_TO=/
HALKPAY_CALLBACK_FAIL_REDIRECT_TO=/
HALKPAY_CALLBACK_SUCCESS_VIEW=halkpay::payments.success
HALKPAY_CALLBACK_FAIL_VIEW=halkpay::payments.fail
```

### 3) Use package routes (or customize)

Default package routes:

- `POST /halkpay/redirect` (`halkpay.redirect`)
- `POST /halkpay/callback` (`halkpay.callback`)

You can customize route enable/prefix/middleware/name prefix in `config/halkpay.php`.

### 4) Send a redirect request

Call the redirect route with at least `amount`.

Typical payload fields:

- `amount` (required)
- `oid` (optional; generated when omitted)
- `customer_name` / `bill_to_name`
- `success_url`, `fail_url`, `callback_url`
- `installment`, `transaction_type`, `currency`, `lang`

## Can success/fail Blade templates be extended?

Yes.

### Option A: Override package views in your project (recommended)

Publish views:

```bash
php artisan vendor:publish --tag=halkpay-views
```

Then edit:

- `resources/views/vendor/halkpay/payments/success.blade.php`
- `resources/views/vendor/halkpay/payments/fail.blade.php`
- `resources/views/vendor/halkpay/payments/redirect.blade.php`

Laravel will automatically prefer your published versions.

### Option B: Point callback to your own view names

Set in `.env` (or directly in `config/halkpay.php`):

```dotenv
HALKPAY_CALLBACK_RESPONSE_MODE=view
HALKPAY_CALLBACK_SUCCESS_VIEW=payments.gateway-success
HALKPAY_CALLBACK_FAIL_VIEW=payments.gateway-fail
```

This lets callback responses render any Blade view in your app.

## Callback handling and security

- Callback hash validation is built in (`HASHPARAMS`, `HASHPARAMSVAL`, `HASH` + `store_key`)
- Middleware alias: `halkpay.hash`
- Callback `response_mode` supports:
  - `json`
  - `redirect`
  - `view`

## Universal integration points

Bind contracts only when you need domain-specific behavior.

```php
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;
use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;

$this->app->bind(TransactionReferenceResolver::class, YourOidResolver::class);
$this->app->bind(CallbackProcessor::class, YourCallbackProcessor::class);
```

If you do not bind them, package defaults are safe no-ops.

## Events

- `Codepreneur\HalkPayGateway\Events\HalkPayCallbackReceived`
