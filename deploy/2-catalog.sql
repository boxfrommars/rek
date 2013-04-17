DROP TABLE "color" CASCADE;
DROP TABLE "surface" CASCADE;
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

INSERT INTO color (title, hex) VALUES ('белый', '#ffffff');
INSERT INTO color (title, hex) VALUES ('чёрный', '#000000');
INSERT INTO color (title, hex) VALUES ('красный', '#ff0000');

CREATE TABLE "surface" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  PRIMARY KEY("id")
);
INSERT INTO surface (title) VALUES ('Полированный');
INSERT INTO surface (title) VALUES ('Неполированный');

CREATE TABLE "country" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,
  PRIMARY KEY("id")
);
INSERT INTO country (title) VALUES ('Россия');
INSERT INTO country (title) VALUES ('Италия');

CREATE TABLE "category" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),
  "description" TEXT,

  "page_url" VARCHAR(255),
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,
             -- описание
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("page_url")
);

INSERT INTO category (title, page_url) VALUES ('Керамогранит', 'keramogranit');
INSERT INTO category (title, page_url) VALUES ('Вентфасады', 'ventfadsadi');

CREATE TABLE "brand" (
  "id" SERIAL NOT NULL,
  "is_published" BOOL DEFAULT 'f',
  "title" VARCHAR(255) NOT NULL,
  "image" VARCHAR (255),
  "description" TEXT,              -- описание
  "id_category" INT REFERENCES category("id"),

  "page_url" VARCHAR(255),
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,

  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("page_url")
);

INSERT INTO brand (title, id_category, page_url) VALUES ('Estima', 1, 'estima');
INSERT INTO brand (title, id_category, page_url) VALUES ('Kerama Marazzi', 2, 'kerama-marazzi');
INSERT INTO brand (title, id_category, page_url) VALUES ('Italon', 1, 'italon');
INSERT INTO brand (title, id_category, page_url) VALUES ('Grassaro', 1, 'grassaro');


CREATE TABLE "product" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,   -- название на русском
  "article" VARCHAR(255) NOT NULL, -- артикул
  "cost" DECIMAL(10,2),            -- стоимость
  "image" VARCHAR (255),
  "thumbnail" VARCHAR (255),

  "page_url" VARCHAR(255),
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,


  "id_color" INT NOT NULL REFERENCES "color" (id),     -- цвет
  "id_surface" INT NOT NULL REFERENCES "surface" (id), -- тип поверхности
  "id_country" INT NOT NULL REFERENCES "country" (id), -- страна
  "id_brand" INT NOT NULL REFERENCES "brand" (id),     -- бренд

  "description" TEXT,              -- описание

  "width" DECIMAL(10,1),           -- размер
  "height" DECIMAL(10,1),          -- высоты
  "depth" DECIMAL(10,1),           -- глубина

  "is_published" BOOL DEFAULT 'f',
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("page_url", "article")
);