.PHONY: cc
cc:
	docker-compose run php bin/console cache:clear

.PHONY: install
install:
	docker-compose run php composer install
	docker-compose run php bin/console doctrine:schema:update --force --no-interaction
	docker-compose run php bin/console doctrine:fixtures:load --no-interaction

.PHONY: start
start:
	docker-compose up --detach

.PHONY: test
test:
	docker-compose run php bin/phpunit
