.DEFAULT_GOAL := help

##@ Help

.PHONY: help
help: ## Show this help
	@awk 'BEGIN {FS = ":.*##"; printf "Usage: make \033[36m<target>\033[0m\n"} /^[a-zA-Z0-9_.-]+:.*##/ { sub(/^ +/, "", $$2); printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) }' $(firstword $(MAKEFILE_LIST))

##@ Project

.PHONY: project
project: install setup ## Install dependencies and create var/ folders

.PHONY: init
init: ## Create config/local.neon from the example
	cp config/local.neon.example config/local.neon

.PHONY: install
install: ## Install dependencies
	composer install

.PHONY: setup
setup: ## Create var/tmp and var/log
	mkdir -p var/tmp var/log
	chmod +0777 var/tmp var/log

.PHONY: clean
clean: ## Remove temporary files and logs
	find var/tmp -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +
	find var/log -mindepth 1 ! -name '.gitignore' -type f,d -exec rm -rf {} +

##@ QA

.PHONY: qa
qa: cs phpstan ## Run code style and static analysis checks

.PHONY: cs
cs: ## Check code style
	vendor/bin/codesniffer app tests

.PHONY: csf
csf: ## Fix code style
	vendor/bin/codefixer app tests

.PHONY: phpstan
phpstan: ## Run static analysis
	vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M

.PHONY: tests
tests: ## Run tests
	vendor/bin/tester -s -p php --colors 1 -C tests

.PHONY: coverage
coverage: ## Generate code coverage (coverage.xml)
	vendor/bin/tester -s -p php --colors 1 -C --coverage ./coverage.xml --coverage-src ./app tests

##@ Development

.PHONY: dev
dev: ## Start the built-in server on port 8000
	NETTE_DEBUG=1 NETTE_ENV=dev php -S 0.0.0.0:8000 -t www

.PHONY: build
build: ## Drop the database, run migrations and load fixtures
	NETTE_DEBUG=1 bin/console orm:schema-tool:drop --force --full-database
	NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction
	NETTE_DEBUG=1 bin/console doctrine:fixtures:load --no-interaction --append

##@ Deployment

.PHONY: deploy
deploy: ## Clean, install, build and clean again
	$(MAKE) clean
	$(MAKE) project
	$(MAKE) build
	$(MAKE) clean

##@ Docker

.PHONY: docker-postgres
docker-postgres: ## Start PostgreSQL in Docker (foreground)
	docker run \
		-it \
		-p 5432:5432 \
		-e POSTGRES_PASSWORD=contributte \
		-e POSTGRES_USER=contributte \
		dockette/postgres:12

.PHONY: docker-adminer
docker-adminer: ## Start Adminer in Docker on port 9999
	docker run \
		-it \
		-p 9999:80 \
		dockette/adminer:dg
