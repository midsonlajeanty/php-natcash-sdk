
# Change Log
All notable changes to this project will be documented in this file.
 
 
## [Unreleased]

## [2.0.1] - 2026-07-03

### Changed
- Republished 2.0.0 under a corrected tag. Packagist immutably locked `2.0.0` to a pre-release commit that still declared `php ^7.4|^8.0`; `2.0.1` is the first correctly published 2.x release (PHP 8.2 floor, deprecated APIs removed). No source changes versus the intended 2.0.0.

## [2.0.0] - 2026-07-03

### Added
- Typed exception hierarchy: `InvalidConfigException`, `InvalidPaymentRequestException`, `ApiException` (subclasses of `NatcashException`).
- Standardized accessors `PaymentRequest::getOrderId()`, `PaymentResponse::getExpiresAt()`, `Core::getClient()/setClient()`.
- Composer scripts `analyse`, `format`, `lint`, `refactor`. CI now runs PHPStan + PHP-CS-Fixer.
- `Config::from()` and `PaymentRequest::from()` as the named constructors.
- `NatcashInterface`, the public contract implemented by the `Natcash` gateway, so consumers can type-hint it and mock it in tests.
- Laravel integration (Laravel 12 and 13): auto-discovered `NatcashServiceProvider`, a `Natcash` facade, and a publishable `config/natcash.php` driven by `NATCASH_*` environment variables. The core SDK stays framework-agnostic; the Laravel layer is opt-in.

### Changed
- **Raised the minimum PHP version to 8.2.** Projects on PHP < 8.2 should stay on the [1.x line](https://github.com/midsonlajeanty/php-natcash-sdk/tree/main) (`composer require "midsonlajeanty/php-natcash-sdk:^1.0"`).
- `NatcashException` is no longer `final` (serves as the base of the exception hierarchy).
- Return types completed (`TransactionDetails`, `Config::toArray()`).
- `Config` and `Constants` are now `final`; value objects are `final readonly`. Mock `NatcashInterface` instead of the gateway; construct value objects directly.
- Dev tooling: replaced PHP-CS-Fixer with Laravel Pint, added Larastan next to PHPStan, and upgraded the test stack to Pest 3 + Testbench. CI now runs a Laravel matrix (PHP 8.2–8.4 × Laravel 12–13) plus a runtime-compatibility job (PHP 8.2–8.5).

### Removed
- **Backward-compatibility shims removed** (they were never part of a documented stable release). Migrate as follows:
  - `PaymentRequest::getOrderNumber()` → `getOrderId()`.
  - `PaymentResponse::getExpiredAt()` → `getExpiresAt()`.
  - `Config::fromArray()` → `Config::from()`; `PaymentRequest::fromArray()` → `PaymentRequest::from()`.

## [1.0.0] - 2026-02-03

### Added
- Create Payment Transaction and get gateway URL  (Natcash Checkout)
- Get Transaction Details by Order ID
