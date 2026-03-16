# Codepreneur HalkPay Gateway

Reusable Laravel package for HalkPay 3D Hosted gateway integrations.

## Why this package is universal

- No hard-coded app domain assumptions (no `order/installment/user` OID format requirement).
- Host applications can provide their own OID resolver through a contract.
- Host applications can provide their own callback processor for domain persistence.
- Callback handling can return JSON or redirect, configurable per project.
- Package routes are configurable (prefix, middleware, route names) and can be disabled.

## Install

```bash
composer require codepreneur/halkpay-gateway
```

## Publish config

```bash
php artisan vendor:publish --tag=halkpay-config
```

## Config (`config/halkpay.php`)

- Gateway credentials / URLs (`client_id`, `store_key`, `gateway_url`, etc.)
- Route behavior (`routes.enabled`, `routes.prefix`, `routes.middleware`, `routes.name_prefix`)
- Callback response mode (`callback.response_mode = json|redirect`)

## Host extension points

### 1) Resolve domain reference from OID (optional)

```php
use Codepreneur\HalkPayGateway\Contracts\TransactionReferenceResolver;

$this->app->bind(TransactionReferenceResolver::class, YourOidResolver::class);
```

### 2) Process callback result in your domain (optional)

```php
use Codepreneur\HalkPayGateway\Contracts\CallbackProcessor;

$this->app->bind(CallbackProcessor::class, YourCallbackProcessor::class);
```

If you do not bind these contracts, the package uses safe no-op defaults.

## Routes

Default:

- `POST /halkpay/redirect` (`halkpay.redirect`)
- `POST /halkpay/callback` (`halkpay.callback`)

## Redirect endpoint request payload

Required:

- `amount`

Optional:

- `oid` (if omitted, ULID is generated)
- `customer_name` / `bill_to_name`
- `callback_url`
- `success_url`
- `fail_url`
- `lang`
- `currency`
- `transaction_type`
- `installment`

## Security

- Callback hash is validated using `HASHPARAMS`, `HASHPARAMSVAL`, and `HASH` + `store_key`.
- Middleware alias: `halkpay.hash`

## Events

- `Codepreneur\HalkPayGateway\Events\HalkPayCallbackReceived`

Listen to this event if you prefer event-driven processing.
