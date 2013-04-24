DROP TABLE "gallery" CASCADE;

CREATE TABLE "gallery" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),

  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id")
);