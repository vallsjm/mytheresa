install:
	docker-compose up -d --build
	docker-compose exec php bash -c "composer install"

start:
	docker-compose up -d

stop:
	docker-compose stop

bash-php:
	docker-compose exec php bash

bash-redis:
	docker-compose exec redis redis-cli

composer-install:
	docker-compose exec php bash -c "composer install"

composer-update:
	docker-compose exec php bash -c "composer update"

cache-clear:
	docker-compose exec php bash -c "./bin/console cache:clear --env=dev"
	docker-compose exec php bash -c "./bin/console cache:clear --env=test"

behat:
	docker-compose exec php bash -c "./vendor/bin/behat --stop-on-failure --strict -n -vv --colors --format pretty"

test:
	docker-compose exec php bash -c "./vendor/bin/phpunit tests --colors=always"

analyze:
	docker-compose exec php bash -c "./vendor/bin/phpstan analyse src/ --level=1"

cs-fix:
	docker-compose exec php bash -c "./vendor/bin/php-cs-fixer fix $1"