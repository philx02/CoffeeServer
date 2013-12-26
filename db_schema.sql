BEGIN TRANSACTION;
CREATE TABLE members (id INTEGER PRIMARY KEY, userid TEXT, username TEXT, password TEXT, name TEXT, email TEXT, balance_cents INTEGER, admin INTEGER);
INSERT INTO members VALUES(1,0123456789,'cayouette','9a73ee8196e7bde96471721da72a708d10e86c0d','Philippe Cayouette','philippe.cayouette@cae.com',0,1);
CREATE TABLE numeric_parameters (id INTEGER PRIMARY KEY, name TEXT, value NUMERIC);
INSERT INTO numeric_parameters VALUES(1,'unit_cost_cents',10);
CREATE TABLE transactions (id INTEGER PRIMARY KEY, date_time TEXT, member_id INTEGER, transaction_type INTEGER, amount_cents INTEGER);
COMMIT;
