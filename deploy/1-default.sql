DROP TABLE IF EXISTS "users" CASCADE;
DROP TABLE IF EXISTS "profile" CASCADE;
DROP TABLE IF EXISTS "role" CASCADE;
DROP TABLE IF EXISTS "news" CASCADE;
DROP TABLE IF EXISTS "faq" CASCADE;
DROP TABLE IF EXISTS "page_text" CASCADE;

CREATE TABLE "role" (
  "id" SERIAL,
  "name" VARCHAR (255),
  PRIMARY KEY ("id")
);

INSERT INTO "role" ("name") VALUES
  ('admin'),
  ('user'),
  ('guest');

CREATE TABLE "users" (
  "id" BIGSERIAL,
  "username" VARCHAR(255) NOT NULL UNIQUE,
  "password" VARCHAR(63) NOT NULL,
  "email" VARCHAR(511),
  "id_role" INT REFERENCES role("id"),

  PRIMARY KEY ("id")
);

INSERT INTO "users" (username, password, email, id_role) VALUES
  ('admin', MD5('admin'), 'admin@example.org', (SELECT "id" FROM "role" WHERE name = 'admin'));

CREATE TABLE "news" (
  "id" BIGSERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "content" TEXT,

  "is_published" BOOL DEFAULT 'f',
  "published_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id")
);

CREATE TABLE "faq" (
  "id" SERIAL NOT NULL,
  "email" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "question" TEXT,
  "answer" TEXT,

  "is_published" BOOL DEFAULT 'f',
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id")
);

CREATE TABLE "page_text" (
  "id" SERIAL NOT NULL,
  "mark" VARCHAR(255) NOT NULL UNIQUE,
  "group" VARCHAR(255) NOT NULL,
  "position" VARCHAR(255) NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "content" TEXT,

  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id")
);