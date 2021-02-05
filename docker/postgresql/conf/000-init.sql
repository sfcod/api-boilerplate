CREATE DATABASE api_boilerplate;
CREATE DATABASE test;
CREATE USER api_boilerplate WITH PASSWORD 'api_boilerplate123';
GRANT ALL PRIVILEGES ON DATABASE api_boilerplate TO api_boilerplate;
GRANT ALL PRIVILEGES ON DATABASE test TO api_boilerplate;
