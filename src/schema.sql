CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL REFERENCES users(id),
    method TEXT NOT NULL,
    reference TEXT,
    total_cents INTEGER NOT NULL,
    status TEXT NOT NULL,
    drawn_numbers TEXT,
    drawn_at TEXT,
    created_at TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS plays (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    order_id INTEGER NOT NULL REFERENCES orders(id),
    numbers TEXT NOT NULL,
    matches INTEGER,
    prize_cents INTEGER
);
CREATE TABLE IF NOT EXISTS past_draws (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    drawn_at TEXT NOT NULL,
    numbers TEXT NOT NULL,
    jackpot_cents INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_plays_order ON plays(order_id);
