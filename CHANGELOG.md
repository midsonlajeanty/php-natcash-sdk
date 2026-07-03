
# Change Log
All notable changes to this project will be documented in this file.
 
 
## [Unreleased]

### Added
- Typed exception hierarchy: `InvalidConfigException`, `InvalidPaymentRequestException`, `ApiException` (subclasses of `NatcashException`).
- Standardized accessors `PaymentRequest::getOrderId()`, `PaymentResponse::getExpiresAt()`, `Core::getClient()/setClient()`.
- Composer scripts `analyse`, `format`, `lint`, `refactor`. CI now runs PHPStan + PHP-CS-Fixer.
- `Config::from()` and `PaymentRequest::from()` as preferred named constructors; `fromArray()` kept as a deprecated alias.
- `NatcashInterface`, the public contract implemented by the `Natcash` gateway, so consumers can type-hint it and mock it in tests.
- Laravel integration (Laravel 9 to 13): auto-discovered `NatcashServiceProvider`, a `Natcash` facade, and a publishable `config/natcash.php` driven by `NATCASH_*` environment variables. The core SDK stays framework-agnostic; the Laravel layer is opt-in.

### Deprecated
- `PaymentRequest::getOrderNumber()` → use `getOrderId()`.
- `PaymentResponse::getExpiredAt()` → use `getExpiresAt()`.
- `Config::fromArray()` → use `Config::from()`.
- `PaymentRequest::fromArray()` → use `PaymentRequest::from()`.

### Changed
- `NatcashException` is no longer `final` (serves as the base of the exception hierarchy).
- Return types completed (`TransactionDetails`, `Config::toArray()`).
- `Config` and `Constants` are now `final` (previously documented `@final` only), matching the already-final value objects and gateway. Mock `NatcashInterface` instead of the gateway; construct value objects directly.
- Dev tooling: replaced PHP-CS-Fixer with Laravel Pint, added Larastan next to PHPStan, and upgraded the test stack to Pest 3 + Testbench. CI now runs a Laravel matrix (PHP 8.2–8.4 × Laravel 11–13) plus a runtime-compatibility job (PHP 7.4–8.5).

## [1.0.0] - 2026-02-03

### Added
- Create Payment Transaction and get gateway URL  (Natcash Checkout)
- Get Transaction Details by Order ID
