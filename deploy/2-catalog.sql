DROP TABLE "color" CASCADE;
DROP TABLE "surface" CASCADE;
DROP TABLE "collection" CASCADE;
DROP TABLE "brand" CASCADE;
DROP TABLE "category" CASCADE;
DROP TABLE "country" CASCADE;
DROP TABLE "product" CASCADE;

CREATE TABLE "color" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  "hex" VARCHAR(7) NOT NULL,
  PRIMARY KEY("id"),
  UNIQUE ("hex")
);

INSERT INTO color (title, hex) VALUES
('белый', '#ffffff'),
('чёрный', '#000000'),
('красный', '#ff0000');

CREATE TABLE "surface" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  PRIMARY KEY("id")
);
INSERT INTO surface (title) VALUES
('Полированный'),
('Неполированный');

CREATE TABLE "country" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  PRIMARY KEY("id")
);
INSERT INTO country (title) VALUES
('Россия'),
('Италия');

CREATE TABLE "category" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),
  "description" TEXT,              -- описание
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("name")
);

INSERT INTO category (title, name) VALUES
('Керамогранит', 'keramogranit'), ('Вентфасады', 'ventfadsadi');

CREATE TABLE "brand" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),
  "description" TEXT,              -- описание
  "id_category" INT REFERENCES category("id"),
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("name")
);

INSERT INTO brand (title, id_category, name) VALUES
('Estima', 1, 'estima'), ('Kerama Marazzi', 2, 'kerama-marazzi'), ('Italon', 1, 'italon'), ('Grassaro', 1, 'grassaro');

CREATE TABLE "collection" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "name" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),
  "description" TEXT,              -- описание
  "id_brand" INT REFERENCES brand("id"),
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("name")
);

INSERT INTO "collection" (title, name, id_brand) VALUES
('Standard', 'standard', 1),
('Jazz NEW', 'jazz', 1),
('Antica', 'antica', 2);


CREATE TABLE "product" (
  "id" SERIAL NOT NULL,
  "name" VARCHAR(255) NOT NULL,    -- название на английском (часть урла)
  "title" VARCHAR(255) NOT NULL,   -- название на русском
  "article" VARCHAR(255) NOT NULL, -- артикул
  "cost" DECIMAL(10,2),            -- стоимость

  "id_color" INT NOT NULL REFERENCES "color" (id),     -- цвет
  "id_surface" INT NOT NULL REFERENCES "surface" (id), -- тип поверхности
  "id_country" INT NOT NULL REFERENCES "country" (id), -- страна
  "id_collection" INT NOT NULL REFERENCES "collection" (id),     -- бренд

  "description" TEXT,              -- описание

  "width" DECIMAL(10,1),           -- размер
  "height" DECIMAL(10,1),          -- высоты
  "depth" DECIMAL(10,1),           -- глубина

  "is_published" BOOL DEFAULT 'f',
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("name", "article")
);