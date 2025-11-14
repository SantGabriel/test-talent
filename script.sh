#!/bin/bash
docker exec -it mysql-talent mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS talent CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec -it mysql-talent mysql -uroot -proot -e "CREATE DATABASE IF NOT EXISTS talent_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

docker exec -it web-talent composer install
docker exec -it web-talent cp .env.example .env
docker exec -it web-talent php artisan migrate
docker exec -it web-talent php artisan db:seed