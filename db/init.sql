-- TODO: create tables

-- CREATE TABLE `examples` (
-- 	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
-- 	`name`	TEXT NOT NULL
-- );


-- TODO: initial seed data

-- INSERT INTO `examples` (name) VALUES ('example-1');
-- INSERT INTO `examples` (name) VALUES ('example-2');

--- Users ---

CREATE TABLE users (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	name TEXT NOT NULL,
	username TEXT NOT NULL UNIQUE,
	password TEXT NOT NULL
);

INSERT INTO users (id, name, username, password) VALUES (1, 'Jiali Chen', 'jiali', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.'); -- password: monkey
INSERT INTO users (id, name, username, password) VALUES (2, 'Sergio Doe', 'sergio', '$2y$10$QtCybkpkzh7x5VN11APHned4J8fu78.eFXlyAMmahuAaNcbwZ7FH.'); -- password: monkey


--- Sessions ---

CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	session TEXT NOT NULL UNIQUE,
    last_login   TEXT NOT NULL,

  FOREIGN KEY(user_id) REFERENCES users(id)
);

--- Groups ----

CREATE TABLE groups (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	name TEXT NOT NULL UNIQUE
);

INSERT INTO groups (id, name) VALUES (1, 'admin');

--- Group Membership

CREATE TABLE memberships (
  id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  group_id INTEGER NOT NULL,
  user_id INTEGER NOT NULL,

  FOREIGN KEY(group_id) REFERENCES groups(id),
  FOREIGN KEY(user_id) REFERENCES users(id)
);

INSERT INTO memberships (group_id, user_id) VALUES (1, 1); -- User 'jiali' is a member of the 'admin' group.

--- Products ---

CREATE TABLE products (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    user_id INTEGER NOT NULL,
	  name TEXT NOT NULL,
	  price REAL NOT NULL,
    description   TEXT NOT NULL,
    filename TEXT NOT NULL,
    file_ext TEXT NOT NULL,
    source TEXT,

  FOREIGN KEY(user_id) REFERENCES users(id)
);
  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (1, 1, 'Apple Fest Mug', 14.99, 'Its a ceramic with apple imprinted on it.', 'mug.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (2, 2, 'Ithaca is Gorges Shirt', 17.89, 'Its a shirt with the words Ithaca is Gorges imprinted on it.', 'tshirt.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (3, 1, 'Apple Cider', 4.69, 'Its a jug with classic apple cider', 'applecider.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (4, 2, 'Random Phone Case', 11.59,'Its an iphone silicon phone case with apple on the back.', 'phonecase.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (5, 1, 'Random Baseball Hat', 13.49, 'Its a random baseball hat.', 'hat.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (6, 2, 'Multi-Color Umbrella', 15.25,'Its an umbrella. With three colors.', 'umbrella.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (7, 2, 'Party Balloons', 5.45,'A three pack party balloons. Enjoy.', 'balloon.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (8, 1, 'Small Succulent', 10.85,'This is a lovely plant. Please take good care of her.', 'succulent.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (9, 2, 'Caramel Apple', 3.99,'This is a lovely apple dipped in caramel sauce with sprinkles.', 'caramelapple.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)

  INSERT INTO products (id, user_id, name, price, description, filename, file_ext, source) VALUES (10, 1, 'Tie Dyed Shirt', 12.34,'This is a tie-dyed short sleeve t-shirt. One size fits all.', 'tiedyeshirt.jpg', 'jpg', '');
    -- source: original work (Jiali Chen)


-- tags --

CREATE TABLE tags (
  id	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  name	TEXT NOT NULL UNIQUE
);

INSERT INTO tags (id, name) VALUES (1, 'Food');
INSERT INTO tags (id, name) VALUES (2, 'Clothing');
INSERT INTO tags (id, name) VALUES (3, 'Apple-Related');
INSERT INTO tags (id, name) VALUES (4, 'Tech');
INSERT INTO tags (id, name) VALUES (5, 'Kitchen');
INSERT INTO tags (id, name) VALUES (6, 'Home');
INSERT INTO tags (id, name) VALUES (7, 'Plant');
INSERT INTO tags (id, name) VALUES (8, 'Toy');
INSERT INTO tags (id, name) VALUES (9, "Jiali's Shop");
INSERT INTO tags (id, name) VALUES (10, "Sergio's Shop");


-- product tags (keeps track of product/tag relations) --

CREATE TABLE product_tags (
  id	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
  product_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,

  FOREIGN KEY(product_id) REFERENCES products(id)
  FOREIGN KEY(tag_id) REFERENCES tags(id)
);

INSERT INTO product_tags (id, product_id, tag_id) VALUES (1, 1, 5);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (2, 1, 6);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (3, 2, 2);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (4, 3, 3);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (5, 3, 5);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (6, 4, 4);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (7, 4, 3);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (8, 5, 2);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (9, 6, 6);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (10,3, 1);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (11,1, 3);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (12,9, 1); --
INSERT INTO product_tags (id, product_id, tag_id) VALUES (13,9, 3); --
INSERT INTO product_tags (id, product_id, tag_id) VALUES (14,10, 2); --
INSERT INTO product_tags (id, product_id, tag_id) VALUES (15,7, 8);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (16,8, 6);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (17,8, 7);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (18,1, 9);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (19,3, 9);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (20,5, 9);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (21,7, 9);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (22,10, 9);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (23,2, 10);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (24,4, 10);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (25,6, 10);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (26,8, 10);
INSERT INTO product_tags (id, product_id, tag_id) VALUES (27,9, 10);
