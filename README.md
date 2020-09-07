# Symfony Test Task

в случае использования докера

- для первой установки:
    - выполнить `make start && make composer-install && make db-reset` 
    
- команды для работы:
    - для остановки докера `make stop`
    - для запуска `make start`
    - перезапуск `make restart`
    - пересоздать базу данных `make db-reset`
    
если не используем докер

- расположить содержимое www на сервере, выполнить         
    - `composer install`
    - `php bin/console doctrine:database:create`
    - `php bin/console doctrine:fixtures:load  --no-interaction`