CREATE DATABASE vodafone CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';
CREATE DATABASE vodafone_test CHARACTER SET = 'utf8' COLLATE = 'utf8_unicode_ci';
CREATE USER 'vodafone'@'%' IDENTIFIED BY 'vodafone123'; 
GRANT ALL PRIVILEGES ON vodafone.* TO 'vodafone'@'%';
GRANT ALL PRIVILEGES ON vodafone_test.* TO 'vodafone'@'%';
FLUSH PRIVILEGES;
