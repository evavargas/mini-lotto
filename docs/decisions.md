# Technical decisions

## Cart in the PHP session
The cart is temporary, per visitor, and needs no history. A session avoids a table, works before login, and survives login (session id is regenerated, the cart is kept). Plays are validated again on checkout input and the price is always computed on the server. Trade-off: the cart is lost when the session expires and is not shared across devices.

## The server decides the draw
`Orders::draw()` generates the numbers with `random_int` (CSPRNG) and computes matches and prizes. The browser only animates the response, so nobody can tamper with a result. The draw is claimed with `UPDATE ... WHERE drawn_numbers IS NULL` inside a transaction, so double clicks or parallel requests cannot draw twice. Draw order is kept for the animation.

## Security
- Prepared statements everywhere; output escaped with `e()`.
- CSRF token in the session, sent as a header by jQuery on every request; POST only on endpoints.
- `password_hash`, session id regenerated on login, generic login error, `HttpOnly`/`SameSite`/`Secure` cookies.
- Orders are always queried by `id` and `user_id`, so users cannot read each other's data.
- Strict CSP: no inline code; CDN assets pinned with SRI.

## Database
SQLite through PDO for zero setup. The DSN, user and password come from `DB_DSN`, `DB_USER`, `DB_PASS`, and queries are standard SQL, so MySQL needs only the schema dialect changed (`AUTOINCREMENT`).

## Payments
Card and OXXO are simulated and both mark the order as paid immediately. Card numbers are never stored. A real OXXO flow would be pending until a webhook confirms it.

## Jackpot and schedule
The jackpot is a base amount plus half of all sales. The next draw is the next Saturday 21:00 UTC. Both are derived, not stored.

## Internationalization and theme
Flat key arrays per language, language kept in the session. The theme uses CSS variables switched by `data-theme`, set before paint by `theme-init.js` from localStorage or `prefers-color-scheme`.

## Accessibility
Skip link, landmarks, `aria-pressed` number buttons, live regions for status and results, hits marked with a check and hidden text (not color alone), visible focus, `prefers-reduced-motion` respected, Bootstrap modal for focus trapping.
