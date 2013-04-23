DROP TABLE "product_color" CASCADE;
DROP TABLE "product" CASCADE;
DROP TABLE "brand" CASCADE;
DROP TABLE "category" CASCADE;
DROP TABLE "country" CASCADE;
DROP TABLE "color" CASCADE;
DROP TABLE "surface" CASCADE;

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

INSERT INTO category (title, page_url, is_published) VALUES ('Керамогранит', 'keramogranit', 't');
INSERT INTO category (title, page_url, is_published) VALUES ('Вентфасады', 'ventfasadi', 't');

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
  "description" TEXT,              -- описание

  "page_url" VARCHAR(255),
  "page_title" VARCHAR (255),
  "page_description" TEXT,
  "page_keywords" TEXT,

  "id_surface" INT NOT NULL REFERENCES "surface" (id), -- тип поверхности
  "id_country" INT NOT NULL REFERENCES "country" (id), -- страна
  "id_brand" INT NOT NULL REFERENCES "brand" (id),     -- бренд

  "is_action" BOOL DEFAULT 'f',
  "is_new" BOOL DEFAULT 'f',
  "is_hit" BOOL DEFAULT 'f',


  "width" DECIMAL(10,1),           -- размер
  "height" DECIMAL(10,1),          -- высоты
  "depth" DECIMAL(10,1),           -- толщина

  "is_published" BOOL DEFAULT 'f',
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id"),
  UNIQUE ("page_url", "article")
);

CREATE TABLE "product_color" (
  "id" SERIAL NOT NULL,
  "title" VARCHAR(255) NOT NULL,                        -- название на русском
  "image" VARCHAR (255),                                -- изображение

  "id_color" INT NOT NULL REFERENCES "color" (id),      -- цвет
  "id_product"  INT NOT NULL REFERENCES "product" (id), -- товар
  "description" TEXT,                                   -- описание

  "is_published" BOOL DEFAULT 'f',
  "created_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  "updated_at" TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NOW(),
  PRIMARY KEY("id")
);

-- DATA

INSERT INTO product (title, article, cost, image, description, page_url, page_title, page_description, page_keywords, id_surface, id_country, id_brand, is_action, is_new, is_hit, width, height, depth, is_published, created_at, updated_at) VALUES ('AGA6625K', 'AGA6625K', 540.00, '', 'Керамогранит Мираж - это новинка, которая интересна тем, что искусно имитирует нерукотворные трещины и канавки в камне, подвергшийся обработке. Этот керамогранит под мрамор еще раз доказывает, что природа может бесконечно давать нам повод восторгаться ее красотой.', 'some1', '', '', '', 1, 1, 1, true, true, true, 12.0, 12.0, 12.0, true, '2013-04-23 02:09:09', '2013-04-23 02:09:09');
INSERT INTO product (title, article, cost, image, description, page_url, page_title, page_description, page_keywords, id_surface, id_country, id_brand, is_action, is_new, is_hit, width, height, depth, is_published, created_at, updated_at) VALUES ('BGA6625K', 'BGA6625K', 740.00, '', 'Керамогранит Мираж - это новинка, которая интересна тем, что искусно имитирует нерукотворные трещины и канавки в камне, подвергшийся обработке. Этот керамогранит под мрамор еще раз доказывает, что природа может бесконечно давать нам повод восторгаться ее красотой.', 'some2', '', '', '', 1, 1, 1, true, true, true, 20.0, 12.0, 12.0, true, '2013-04-23 02:09:09', '2013-04-23 02:09:09');
INSERT INTO product (title, article, cost, image, description, page_url, page_title, page_description, page_keywords, id_surface, id_country, id_brand, is_action, is_new, is_hit, width, height, depth, is_published, created_at, updated_at) VALUES ('CGA6625K', 'CGA6625K', 340.00, '', 'Керамогранит Мираж - это новинка, которая интересна тем, что искусно имитирует нерукотворные трещины и канавки в камне, подвергшийся обработке. Этот керамогранит под мрамор еще раз доказывает, что природа может бесконечно давать нам повод восторгаться ее красотой.', 'some3', '', '', '', 1, 1, 1, true, true, true, 12.0, 30, 12.0, true, '2013-04-23 02:09:09', '2013-04-23 02:09:09');
INSERT INTO product (title, article, cost, image, description, page_url, page_title, page_description, page_keywords, id_surface, id_country, id_brand, is_action, is_new, is_hit, width, height, depth, is_published, created_at, updated_at) VALUES ('DGA6625K', 'DGA6625K', 600.00, '', 'Керамогранит Мираж - это новинка, которая интересна тем, что искусно имитирует нерукотворные трещины и канавки в камне, подвергшийся обработке. Этот керамогранит под мрамор еще раз доказывает, что природа может бесконечно давать нам повод восторгаться ее красотой.', 'some4', '', '', '', 1, 1, 1, true, true, true, 12.0, 30, 12.0, true, '2013-04-23 02:09:09', '2013-04-23 02:09:09');

DELETE FROM news WHERE 1=1;
INSERT INTO news (title, content, is_published, published_at) VALUES ('Акция на полированный керамогранит в ноябре', '<p>Feeling offended by Gregor''s delayed response in opening the door, the clerk warns him of the consequences of missing work. He adds that his recent performance has been unsatisfactory. Gregor disagrees and tells him that he will open the door shortly. Nobody on the other side of the door could understand a single word he uttered (Gregor was unaware of the fact that his voice has also transformed) and conclude that he is seriously ill. Finally, Gregor manages to unlock and open the door with his mouth. He apologizes to the office manager for the delay. Horrified by the sight of Gregor''s appearance, the manager bolts out of the apartment, while Gregor''s mother faints. Gregor tries to catch up with him but his father drives him back into the bedroom with a cane and a rolled newspaper. Gregor injures himself squeezing back through the doorway, and his father slams the door shut. Gregor, exhausted, falls asleep.</p>','t', '2012-11-06' );
INSERT INTO news (title, content, is_published, published_at) VALUES ('Расширение линейки полированного керамогранита ', '','t', '2012-10-31');
INSERT INTO news (title, content, is_published, published_at) VALUES ('Специальная цена до 1 ноября - белый полированный керамогранит!', '','t', '2012-10-15');

DELETE FROM page_text WHERE 1 = 1;

INSERT INTO page_text (mark, "group", "group_title", "position", title, content, created_at, updated_at) VALUES ('first', 'main', 'Главная', 'Почему выбирают нас / первый блок', 'Заголовок 1', '<p>При необходимости срочного приобретения керамогранита, покупатель сталкивается с ситуацией, когда на складе продавца не всегда имеется необходимое количество материала. Отечественные производители КЕРАТОН, KERAMA MARAZZI, Italon, Grasaro заблаговременно осуществляют доставку  продукции дистрибьюторам в количестве, соответствующем спросу и объемам продаж. Поэтому компания Рекада всегда располагает достаточным количеством керамогранита Эстима и любых коллекций KERAMA MARAZZI, Italon и Grasaro. <br> В случае, когда клиент желает приобрести керамогранит оптом, он может заранее оформить заявку, чтобы мы успели</p>', '2013-04-23 04:22:44', '2013-04-23 04:22:44');
INSERT INTO page_text (mark, "group", "group_title", "position", title, content, created_at, updated_at) VALUES ('second', 'main', 'Главная', 'Почему выбирают нас / второй блок', 'Заголовок 2', '<p>При необходимости срочного приобретения керамогранита, покупатель сталкивается с ситуацией, когда на складе продавца не всегда имеется необходимое количество материала. Отечественные производители КЕРАТОН, KERAMA MARAZZI, Italon, Grasaro заблаговременно осуществляют доставку  продукции дистрибьюторам в количестве, соответствующем спросу и объемам продаж. Поэтому компания Рекада всегда располагает достаточным количеством керамогранита Эстима и любых коллекций KERAMA MARAZZI, Italon и Grasaro. <br> В случае, когда клиент желает приобрести керамогранит оптом, он может заранее оформить заявку, чтобы мы успели</p>', '2013-04-23 04:22:44', '2013-04-23 04:22:44');
INSERT INTO page_text (mark, "group", "group_title", "position", title, content, created_at, updated_at) VALUES ('third', 'main', 'Главная', 'Почему выбирают нас / третий блок', 'Заголовок 3', '<p>При необходимости срочного приобретения керамогранита, покупатель сталкивается с ситуацией, когда на складе продавца не всегда имеется необходимое количество материала. Отечественные производители КЕРАТОН, KERAMA MARAZZI, Italon, Grasaro заблаговременно осуществляют доставку  продукции дистрибьюторам в количестве, соответствующем спросу и объемам продаж. Поэтому компания Рекада всегда располагает достаточным количеством керамогранита Эстима и любых коллекций KERAMA MARAZZI, Italon и Grasaro. <br> В случае, когда клиент желает приобрести керамогранит оптом, он может заранее оформить заявку, чтобы мы успели</p>', '2013-04-23 04:22:44', '2013-04-23 04:22:44');
