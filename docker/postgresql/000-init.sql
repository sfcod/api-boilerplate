CREATE DATABASE api_boilerplate;
CREATE DATABASE api_boilerplate_test;
CREATE USER api_boilerplate WITH PASSWORD 'api_boilerplate123';
GRANT ALL PRIVILEGES ON DATABASE api_boilerplate TO api_boilerplate;
GRANT ALL PRIVILEGES ON DATABASE api_boilerplate_test TO api_boilerplate;
