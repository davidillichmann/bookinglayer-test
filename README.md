# Bookinglayer-test
Bookinglayer coding test

## Task description
[Task](TASK.md)

## Install
https://laravel.com/docs/9.x/sail#installing-composer-dependencies-for-existing-projects

    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
    
Run 

    ./vendor/bin/sail up
    

App will be available here:

    http://localhost/

## Tests

    ./vendor/bin/sail artisan test
