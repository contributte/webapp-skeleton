############################################################
# PROJECT ##################################################
############################################################
.PHONY: project
project: install setup

.PHONY: init
init:
	cp config/local.neon.example config/local.neon

.PHONY: install
install:
	composer install

.PHONY: setup
setup:
	mkdir -p var/tmp var/log
	chmod +0777 var/tmp var/log

.PHONY: clean
clean:
	find var/tmp -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +
	find var/log -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +

############################################################
# DEVELOPMENT ##############################################
############################################################
.PHONY: qa
qa: cs phpstan

.PHONY: cs
cs:
	vendor/bin/codesniffer app tests

.PHONY: csf
csf:
	vendor/bin/codefixer app tests

.PHONY: phpstan
phpstan:
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M

.PHONY: tests
tests:
	vendor/bin/tester -s -p php --colors 1 -C tests

.PHONY: coverage
coverage:
	vendor/bin/tester -s -p phpdbg --colors 1 -C --coverage ./coverage.xml --coverage-src ./app tests

.PHONY: dev
dev:
	NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

.PHONY: build
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
.PHONY: docker-postgres
docker-postgres:
	docker run \
		-it \
		-p 5432:5432 \
		-e POSTGRES_PASSWORD=contributte \
		-e POSTGRES_USER=contributte \
		dockette/postgres:12

.PHONY: docker-adminer
docker-adminer:
	docker run \
		-it \
		-p 9999:80 \
		dockette/adminer:dg
