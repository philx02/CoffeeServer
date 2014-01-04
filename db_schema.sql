BEGIN TRANSACTION;

CREATE TABLE members
(
  id INTEGER PRIMARY KEY,
  userid TEXT UNIQUE NOT NULL,
  username TEXT UNIQUE NOT NULL,
  password TEXT NOT NULL,
  name TEXT NOT NULL,
  email TEXT UNIQUE NOT NULL,
  balance_cents INTEGER NOT NULL DEFAULT 0,
  admin INTEGER NOT NULL DEFAULT 0
);

CREATE TABLE numeric_parameters
(
  id INTEGER PRIMARY KEY,
  name TEXT UNIQUE NOT NULL,
  value NUMERIC NOT NULL
);

CREATE TABLE transactions
(
  id INTEGER PRIMARY KEY,
  date_time TEXT NOT NULL,
  member_id INTEGER NOT NULL,
  transaction_type INTEGER NOT NULL,
  amount_cents INTEGER NOT NULL,
  FOREIGN KEY (member_id) REFERENCES members (id) ON DELETE CASCADE
  FOREIGN KEY (transaction_type) REFERENCES transactions_name (transaction_type) ON DELETE SET NULL
);

CREATE TABLE transactions_name
(
  id INTEGER PRIMARY KEY,
  transaction_type INTEGER UNIQUE NOT NULL,
  name TEXT NOT NULL
);

INSERT INTO transactions_name VALUES (1,0,'Initial deposit');
INSERT INTO transactions_name VALUES (2,1,'Coffee purchase');
INSERT INTO transactions_name VALUES (3,2,'Deposit');
INSERT INTO numeric_parameters VALUES(1,'unit_cost_cents',10);
INSERT INTO members VALUES(1,123456789,'cayouette','9a73ee8196e7bde96471721da72a708d10e86c0d','Philippe Cayouette','philippe.cayouette@cae.com',1000,1);
INSERT INTO transactions SELECT 1, datetime('now', 'localtime'), 1, 0, 1000;

COMMIT;
