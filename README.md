1. сначала собрать docker-compose
    docker-compose up -d (можно изменить внешний порт для nginx по желанию)

2. в контейнере php-fpm зайти в cli и запустить composer install
    docker exec -it <container name> /bin/bash

3. запустить браузер по адресу localhost:8003
