.PHONY: qa cs cfx phpstan tests build

qa:
	composer qa

cs:
	composer cs

cfx:
	composer cfx

phpstan:
	composer phpstan

tests:
	composer tests

tests-coverage:
	composer tests-coverage

#####################
# LOCAL DEVELOPMENT #
#####################

build:
	NETTE_DEBUG=1 bin/console orm:schema-tool:drop --force --full-database
	NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction
	NETTE_DEBUG=1 bin/console doctrine:fixtures:load --no-interaction --append

loc-web:
	NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

loc-web-prod:
	NETTE_ENV=prod php -S 0.0.0.0:8000 -t www

loc-postgres: loc-postgres-stop
	docker run -it -d -p 5432:5432 --name webapp_postgres -e POSTGRES_PASSWORD=webapp -e POSTGRES_USER=webapp postgres:10

loc-postgres-stop:
	docker stop webapp_postgres || true
	docker rm webapp_postgres || true

loc-adminer: loc-adminer-stop
	docker run -it -d -p 9999:80 --name webapp_adminer dockette/adminer:dg

loc-adminer-stop:
	docker stop webapp_adminer || true
	docker rm webapp_adminer || true
