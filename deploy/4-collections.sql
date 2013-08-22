DROP TABLE "collection";
CREATE TABLE "collection" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  PRIMARY KEY("id")
);
ALTER TABLE "product" ADD COLUMN "id_collection" INT REFERENCES "collection" (id)  ON DELETE CASCADE;