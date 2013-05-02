DROP TABLE "page";

CREATE TABLE "page" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',

  "title" VARCHAR(255) NOT NULL,
  "content" TEXT,

  "page_url" VARCHAR(255) UNIQUE,
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,

  "name" VARCHAR(255) UNIQUE,
  "is_locked" BOOL DEFAULT 'f',
  "path" LTREE,
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),

  PRIMARY KEY("id")
);

CREATE INDEX ON page USING GIST (path);
INSERT INTO "page" (is_published, title, content, page_url, page_title, is_locked, path, name) VALUES ('t', 'Главная', '', '', 'Главная', 't', 'Top', 'main');
INSERT INTO "page" (is_published, title, content, page_url, page_title, is_locked, path, name) VALUES ('t', 'Галерея', '', 'gallery', 'Галерея', 't', 'Top.Second', 'gallery');