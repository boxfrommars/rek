\c postgres
DROP DATABASE IF EXISTS flavia;
CREATE ROLE flavia ENCRYPTED PASSWORD 'flavia' LOGIN;
CREATE DATABASE flavia OWNER flavia;
GRANT ALL ON DATABASE flavia TO flavia;
\c flavia