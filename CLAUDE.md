# Mini Lotto

Fictional lottery proof of concept. PHP 8 without a framework, jQuery 3.3.1, Bootstrap 4.6.2 (CDN), SQLite via PDO. No build step.

## Run

```
php setup.php
php -S localhost:8000 -t public
php -d zend.assertions=1 -d assert.exception=1 tests/selfcheck.php
```

## Structure

- `public/`: web root. Pages (`*.php`), `_ajax/` JSON endpoints, `assets/`.
- `src/`: logic. One static class per file, autoloaded by file name in `bootstrap.php`.
- `views/`: templates. `layout.php` wraps each page view through `render()`.
- `lang/`: `en.php` and `es.php`. Keys prefixed `js.` are sent to the browser.
- `data/`: SQLite file (git-ignored). `setup.php` creates it from `src/schema.sql`.

## Conventions

- Everything in English. No code comments.
- Every query is a prepared statement through `Db::run()`.
- Escape all output with `e()`. Every user-facing string goes through `t()` and exists in both languages.
- Every POST goes to `public/_ajax/` and starts with `Http::post()`, which enforces the `X-CSRF-Token` header. Errors return `{"error": "..."}`.
- Money is stored as integer cents.
- No inline scripts or styles (the CSP forbids them). Add data to `data-*` attributes.
- Colors come from CSS variables in `app.css`; check both themes.
- Interactive elements need labels, visible focus, and must work by keyboard.
- To add a language: new file in `lang/` and add it to `I18n::LANGS`.

## Docs

Design decisions are in `docs/decisions.md`.
