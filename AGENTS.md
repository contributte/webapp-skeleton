# Webapp Skeleton

Instructions for AI coding agents working in this repository.

## Overview

A Nette application skeleton with a front module, an admin module with sign-in, Doctrine ORM on PostgreSQL,
Symfony Console, mailing and PDF examples, and Nginx, PHP-FPM and PostgreSQL in Docker Compose. It is a starting
point that users copy with `composer create-project`, not a library. Every change must keep a fresh copy working.

- **PHP**: 8.4 and later (`>=8.4` in `composer.json`), CI runs 8.4 only
- **Package**: `contributte/webapp-skeleton`, `"type": "project"`
- **Namespaces**: `App\` in `app/`, `Database\` in `db/`, `Tests\` in `tests/`

## Documentation

- `PRD.md` says what the skeleton demonstrates and what it leaves out on purpose. Read it before adding a feature
  or a package.
- `TECH.md` explains the bootstrap, the configuration layers, the database and Docker setup, and lists the known
  limits. Read it before changing `app/Bootstrap.php`, `config/` or `docker-compose.yml`.
- `DESIGN.md` holds the rules for layouts, templates and assets in `app/UI/`, `resources/` and `www/assets/`.
- Organization rules are in [contributte/contributte specs](https://github.com/contributte/contributte/tree/master/specs).

## Stack

- **Framework**: Nette 3.2, Latte 3, Tracy 2.11, `contributte/*` integrations
- **Database**: PostgreSQL via `nettrine/dbal`, `nettrine/orm` (Doctrine ORM 3), `nettrine/migrations` and
  `nettrine/fixtures`
- **QA**: `contributte/qa` (CodeSniffer), PHPStan level 9 with `phpstan-doctrine`, Nette Tester with
  `contributte/tester`

```
app/
├── Bootstrap.php     # boot(), runWeb(), runCli(); picks config/env/dev.neon or prod.neon
├── Console/          # HelloCommand
├── Domain/           # User entity, repository, query, facade; Order event; subscribers
├── Model/            # infrastructure: Database, Security, Router, Latte, Utils, Exception
└── UI/               # Modules/{Admin,Base,Front,Mailing,Pdf}, Control/, Form/
config/               # app/, env/, ext/; local.neon is created by make init
db/                   # Migrations/ and Fixtures/ (namespace Database\)
resources/            # mail, pdf and Tracy error templates
www/                  # the only public directory: index.php, assets/
```

## Commands

```bash
# Install dependencies, create var/tmp and var/log, copy config/local.neon.example to config/local.neon
make project
make init

# Built-in server on http://localhost:8000 with NETTE_DEBUG=1 and NETTE_ENV=dev
make dev

# Or nginx (8080, 8443), php-fpm and PostgreSQL in Docker; there is no make target for it
docker compose up -d

# Drop the database, migrate, load fixtures; code style + PHPStan, fix code style, run tests, or one test file
make build
make qa
make csf
make tests
vendor/bin/tester -s -p php --colors 1 -C tests/Cases/E2E/Container/EntrypointTest.php
```

CI runs `make init tests`, `make init phpstan`, CodeSniffer, coverage and migrations on PostgreSQL 16. No `help` target.

## Conventions

- A presenter lives in `app/UI/Modules/{Module}/{Name}/{Name}Presenter.php`, its templates in `templates/` next to
  it. Module layouts are `app/UI/Modules/{Module}/templates/@layout.latte`; new modules also need a line in
  `application.mapping` in `config/env/base.neon` and a route in `RouterFactory`.
- Services are registered by hand in `config/app/services.neon`; there is no `search:` section.
- Schema changes go through a new file in `db/Migrations`. Never edit a migration that has been released.
- `composer.lock` is committed; update it together with `composer.json`.

## Traps

- **`NETTE_ENV=dev` loads `config/env/dev.neon`; any other value, including `test`, loads `prod.neon`.**
  `config/local.neon` is loaded last and must exist, or the container does not build.
- **`make init` overwrites `config/local.neon` without asking.** Its host `0.0.0.0` works for `make dev`; in Docker
  set it to `database`.
- **`make build` drops the whole database** (`orm:schema-tool:drop --full-database`), then migrates and loads
  fixtures. Never point it at a database with real data.
- **The Docker `php` container purges and reloads fixtures on every start** (`doctrine:fixtures:load` without
  `--append`). Data you create in Docker is lost on restart.
- **Doctrine ORM 3 reads only attributes, but `User` and the `TId`, `TCreatedAt`, `TUpdatedAt` traits use `@ORM`
  docblocks.** Doctrine sees no entity, so the mapping test passes with nothing to check. See `TECH.md`.
- **`nginx` and `adminer` both publish host port 8080 in `docker-compose.yml`.** Change one before
  `docker compose up`, see `TECH.md` Known Limits.
- **Opening `/mailing` sends an e-mail** to `foo@example.com` on every GET request.
- **Templates see the user as `$_user`, not `$user`.** `App\Model\Latte\TemplateFactory` removes `$user`.
- Features for users and screenshots are described in `README.md`; product scope is in `PRD.md`, not here.

## Ground Rules

- **Only `www/` is public.** `app/`, `config/`, `db/`, `resources/` and `var/` must never be served.
- Secrets live in `config/local.neon` (git-ignored) or environment variables and are never committed.
- Tracy debug mode is on only with `NETTE_DEBUG=1`. `docker-compose.yml` sets it and loads the fixture user
  `admin@admin.cz` / `admin`, so the Docker stack and its credentials are for local use only.
