############################################################
# PROJECT ##################################################
############################################################
.PHONY: project install setup clean

project: install setup

install:
	composer install

setup:
	mkdir -p var/tmp var/log
	chmod +0777 var/tmp var/log

clean:
	find var/tmp -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +
	find var/log -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +

############################################################
# DEVELOPMENT ##############################################
############################################################
.PHONY: qa dev cs csf phpstan tests coverage dev build

qa: cs phpstan

cs:
	vendor/bin/codesniffer app tests

csf:
	vendor/bin/codefixer app tests

phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M app tests/toolkit

tests:
	vendor/bin/tester -s -p php --colors 1 -C tests

coverage:
	vendor/bin/tester -s -p phpdbg --colors 1 -C --coverage ./coverage.xml --coverage-src ./app tests

dev:
	NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

build:
	NETTE_DEBUG=1 bin/console orm:schema-tool:drop --force --full-database
	NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction
	NETTE_DEBUG=1 bin/console doctrine:fixtures:load --no-interaction --append

############################################################
# DEPLOYMENT ###############################################
############################################################
.PHONY: deploy

deploy:
	$(MAKE) clean
	$(MAKE) project
	$(MAKE) build
	$(MAKE) clean

############################################################
# DOCKER ###################################################
############################################################
.PHONY: docker-postgres docker-postgres-stop docker-adminer docker-adminer-stop

docker-postgres: docker-postgres-stop
	docker run -it -d -p 5432:5432 --name webapp_postgres -e POSTGRES_PASSWORD=webapp -e POSTGRES_USER=webapp dockette/postgres:12

docker-postgres-stop:
	docker stop webapp_postgres || true
	docker rm webapp_postgres || true

docker-adminer: docker-adminer-stop
	docker run -it -d -p 9999:80 --name webapp_adminer dockette/adminer:dg

docker-adminer-stop:
	docker stop webapp_adminer || true
	docker rm webapp_adminer || true
