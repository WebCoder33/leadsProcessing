1. сначала собрать docker-compose
    docker-compose up -d (можно изменить внешний порт для nginx по желанию)

2. в контейнере php-fpm зайти в cli и запустить 
    docker exec -it <container name> /bin/bash потом composer install

3. запустить браузер по адресу localhost:8003
