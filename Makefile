build:
	docker-compose up --build -d

stop:
	docker-compose down

install:
	docker exec -it symfony-php-fpm composer install

migrate:
	docker exec symfony-php-fpm /bin/sh -c "php bin/console make:migration -n"

doctrine-migrate:
	docker exec symfony-php-fpm /bin/sh -c "php bin/console doctrine:migrations:migrate -n"

fake-data:
#     refresh fake data to db
	docker exec symfony-php-fpm /bin/sh -c "php bin/console doctrine:fixtures:load -n"