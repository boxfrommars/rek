DROP TABLE "page";

CREATE TABLE "page" (
  "id" SERIAL NOT NULL,
  "id_parent" INT REFERENCES "page" ("id") DEFAULT NULL,
  "is_published" BOOL DEFAULT 'f',

  "title" VARCHAR(255) NOT NULL,
  "content" TEXT,

  "page_url" VARCHAR(255),
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,

  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("page_url")
);

INSERT INTO "page" (is_published, title, content, page_url, page_title) VALUES ('t', 'first dynamic page', 'wapa', 'firstpage', 'Первая');
INSERT INTO "page" (id_parent, is_published, title, content, page_url, page_title) VALUES (1, 't', 'second dynamic page', 'wapa2', 'secondpage', 'Вторая');

