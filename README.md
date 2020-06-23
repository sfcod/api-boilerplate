# Symfony api-boilerplate

## Admin part
- Sonata admin

## Api part
- Api platform 

#### Run:
``` bash
$ composer install
$ npm install
$ npm run dev
$ php init.php
```

#### Generate the SSH keys:

``` bash
$ mkdir -p config/jwt
$ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
$ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```

#### PHPUnit:
```bash 
php bin/console do:mi:mi --env=test
php bin/console do:fi:lo --env=test
```
``` bash
$ php bin/phpunit
$ php bin/phpunit --filter SecurityControllerTest
```
