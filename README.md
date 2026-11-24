# Mini Lotto

A small proof of concept of a fictional online lottery. No real money. Plain PHP 8 (no framework), jQuery 3.3.1 and Bootstrap 4.6.2 from CDN, SQLite through PDO. No build step.

## Run it in 2 commands

```
php setup.php
php -S localhost:8000 -t public
```

Open http://localhost:8000. Demo account: `demo@example.com` / `Demo1234!`. Test card: `4242 4242 4242 4242`.

Checks: `php -d zend.assertions=1 -d assert.exception=1 tests/selfcheck.php`

## Features

Home with countdown and jackpot, number picker (client and server validation), session cart, AJAX login and register, simulated checkout (test card or fake OXXO reference), instant draw with animation (the server decides the result), order history, English and Spanish, light and dark themes, keyboard and screen reader support.

## Deploy for free (Render)

Render's free web service runs Docker images, gives HTTPS, and deploys on every push.

1. Push this folder to a GitHub repository.
2. In Render: New > Blueprint, select the repo. `render.yaml` and `Dockerfile` do the rest.
3. The container runs `setup.php` on start, then Apache.

Free tier limits: the service sleeps after ~15 minutes idle (first request takes about a minute) and the disk is ephemeral, so SQLite data resets on each restart or deploy. That is fine for a demo; setup re-seeds the data. For persistence, point `DB_DSN`, `DB_USER` and `DB_PASS` at a free MySQL/Postgres (for example Aiven or TiDB Cloud) and port `src/schema.sql` (the `AUTOINCREMENT` keyword).

Practices applied: DocumentRoot is `public/` only (code and DB are not web-reachable), production `php.ini`, hidden server version, security headers and a strict CSP, `Secure`/`HttpOnly`/`SameSite` session cookies, SRI on CDN assets, health check, CI that lints and smoke-tests every push. Secrets belong in Render environment variables, never in the repo.

## What I would improve in production

- Real payment gateway with webhooks (OXXO payments confirmed asynchronously, idempotency keys).
- Queues and workers for draws, payouts and emails, instead of drawing in the request.
- Rate limiting and lockout on login, register and checkout; bot protection.
- Structured logs, error tracking, metrics and alerts.
- Automated tests: unit, integration with a real database, end-to-end; CI gates.
- Accessibility audit with real assistive technology, plus full locale-aware number and date formatting (intl).
- MySQL/Postgres with migrations, backups, and sessions stored outside the web server.
- A non-root container and a pinned base image with automated updates.
