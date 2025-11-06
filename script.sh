#!/bin/bash
docker exec -it web-talent chown -R 1000:1000 ./
docker exec -it web-talent chown -R 1000:www-data storage bootstrap/cache
docker exec -it web-talent chmod -R 775 storage bootstrap/cache
docker exec -it web-talent php artisan migrate

docker exec -it mysql-talent mysql -uroot -ppassword -e "CREATE DATABASE IF NOT EXISTS talent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"