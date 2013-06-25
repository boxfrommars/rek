-- in db postgres with postgres user
-- CREATE DATABASE rekada OWNER xu ENCODING 'UTF8' LC_COLLATE 'ru_RU.UTF-8' LC_CTYPE 'ru_RU.UTF-8' template template0;
\c postgres
DROP DATABASE IF EXISTS flavia;
DROP ROLE IF EXISTS flavia;
CREATE ROLE flavia ENCRYPTED PASSWORD 'flavia' LOGIN;
CREATE DATABASE flavia OWNER flavia;
GRANT ALL ON DATABASE flavia TO flavia;
\c flavia
CREATE EXTENSION ltree;