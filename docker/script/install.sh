docker-compose exec app composer self-update --stable
docker-compose exec app composer update
docker-compose exec app composer dump-autoload
docker-compose exec app composer install
docker-compose exec app php artisan vendor:publish
docker-compose exec app npm install