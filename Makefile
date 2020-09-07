start:
	docker-compose up -d

stop:
	docker-compose down

composer-install:
	docker-compose exec php71 composer install

db-reset:
	make db-create
	make db-migrate
	make db-load-fixtures

db-create:
	docker-compose exec php71 php bin/console doctrine:database:drop --force; \
	docker-compose exec php71 php bin/console doctrine:database:create;

db-migrate:
	docker-compose exec php71 php bin/console doctrine:migrations:migrate --no-interaction

db-load-fixtures:
	docker-compose exec php71 php bin/console doctrine:fixtures:load  --no-interaction