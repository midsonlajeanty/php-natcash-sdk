
# Change Log
All notable changes to this project will be documented in this file.
 
 
## [Unreleased]

### Added
- Typed exception hierarchy: `InvalidConfigException`, `InvalidPaymentRequestException`, `ApiException` (subclasses of `NatcashException`).
- Standardized accessors `PaymentRequest::getOrderId()`, `PaymentResponse::getExpiresAt()`, `Core::getClient()/setClient()`.
- Composer scripts `analyse`, `format`, `lint`, `refactor`. CI now runs PHPStan + PHP-CS-Fixer.
- `Config::from()` and `PaymentRequest::from()` as preferred named constructors; `fromArray()` kept as a deprecated alias.
- `NatcashInterface`, the public contract implemented by the `Natcash` facade, so consumers can type-hint it and mock the gateway in tests.

### Deprecated
- `PaymentRequest::getOrderNumber()` → use `getOrderId()`.
- `PaymentResponse::getExpiredAt()` → use `getExpiresAt()`.
- `Config::fromArray()` → use `Config::from()`.
- `PaymentRequest::fromArray()` → use `PaymentRequest::from()`.

### Changed
- `NatcashException` is no longer `final` (serves as the base of the exception hierarchy).
- Return types completed (`TransactionDetails`, `Config::toArray()`).
- `Config` and `Constants` are now `final` (previously documented `@final` only), matching the already-final value objects and facade. Mock `NatcashInterface` instead of the facade; construct value objects directly.

## [1.0.0] - 2026-02-03

### Added
- Create Payment Transaction and get gateway URL  (Natcash Checkout)
- Get Transaction Details by Order ID
