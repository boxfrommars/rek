DROP TABLE "settings";

CREATE TABLE "settings" (
  "id" SERIAL NOT NULL,

  "title" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) UNIQUE,
  "value" VARCHAR(511),
  PRIMARY KEY("id")
);

INSERT INTO settings (title, name, value) VALUES ('email', 'email', 'boxfrommars@gmail.com');
INSERT INTO settings (title, name, value) VALUES ('Прайс', 'price', '');