CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created DATETIME,
    modified DATETIME
);

CREATE TABLE items (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    status INTEGER NOT NULL DEFAULT 0,
    note TEXT,
    created DATETIME,
    modified DATETIME
);

CREATE TABLE loans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    item_id INTEGER NOT NULL REFERENCES items(id),
    user_id INTEGER NOT NULL REFERENCES users(id),
    loaned_on DATE NOT NULL,
    due_on DATE NOT NULL,
    returned_on DATE,
    accessories VARCHAR(255) NOT NULL DEFAULT '',
    accessory_count INTEGER NOT NULL DEFAULT 0,
    created DATETIME,
    modified DATETIME
);
