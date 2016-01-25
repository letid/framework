# Composer

curl -s http://getcomposer.org/installer | php
php -r "readfile('https://getcomposer.org/installer');" | php
composer dump-autoload -o

Installing dependencies
---
composer install
composer install -o --no-dev

Updating dependencies
---
composer update
composer update -o --no-dev
composer require khensolomonlethil/lethil
composer remove khensolomonlethil/lethil
composer create-project khensolomonlethil/lethil blog

composer update --lock
php composer.phar --lock install

Directory
---
- zomi
    - vendor-dir
    - config-dir
    - public-dir
    - project-dir
    - development-dir
    - production-dir
    - resource-dir

Class
---
- App
    - Initiate
        - Request
        - Response
    - Request
    - Configuration
