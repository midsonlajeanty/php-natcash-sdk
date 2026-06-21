# Security Policy

## Supported Versions

| Version | Supported          |
| :------ | :----------------- |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

If you discover a security vulnerability within this project, please **do not** report it through public GitHub issues.

Instead, please use one of the following private channels:

- **GitHub Private Vulnerability Reporting** (Preferred):
  [Report a vulnerability](https://github.com/midsonlajeanty/php-natcash-sdk/security/advisories/new)
- **Email**: [midsonlajeanty@proton.me](mailto:midsonlajeanty@proton.me)

### What to include

To help us address the issue quickly, please include as much detail as possible:

- **Type of issue**: (e.g., signature bypass, credential leak, request forgery, etc.)
- **Affected version(s)**
- **Steps to reproduce**: Ideally with a minimal snippet.
- **Potential impact**: How this vulnerability could be exploited.

### Our Response

You will receive an initial response within **72 hours**. Once the issue is confirmed, a fix will be prepared and released as quickly as possible. You will be credited in the release notes unless you prefer to remain anonymous.

## Scope Notes

This SDK handles payment credentials (private key, partner credentials) and HMAC-signed payloads to the Natcash gateway. Never commit real credentials, and never paste secrets or signed payloads into a public issue. Treat the contents of `example/constant.php` and `.env` as secrets — they are gitignored on purpose.
