<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
![pipeline status](https://gitlab.com/lucjfer0407/mt6/badges/master/pipeline.svg)
</p>

docker run --rm -v "/$(pwd)":/var/www/html -w //var/www/html composer update --ignore-platform-reqs

docker run --rm -v "/$(pwd)":/var/www/html -w //var/www/html composer install --ignore-platform-reqs --no-autoloader --no-dev --no-interaction --no-progress --no-suggest --no-scripts --prefer-dist

docker run --rm -v "/$(pwd)":/var/www/html -w //var/www/html composer dump-autoload --classmap-authoritative --no-dev --optimize

docker run --rm -v "/$(pwd)":/var/www/html -w //var/www/html node npm install --production

docker run --rm -v "/$(pwd)":/var/www/html -w //var/www/html node npm run prod
