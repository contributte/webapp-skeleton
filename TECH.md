# Webapp Skeleton Tech

A Nette Framework application with Doctrine ORM on PostgreSQL, served by Nginx and PHP-FPM in Docker Compose or
by the PHP built-in server.

## Architecture

```
browser -> nginx (:8080) -> php-fpm (php:9000) -> www/index.php -> App\Bootstrap::runWeb()
                                                  -> Nette Application -> RouterFactory -> presenter -> Latte
make dev -> php -S 0.0.0.0:8000 -t www -> www/index.php (same path as above)
bin/console -> App\Bootstrap::runCli() -> Symfony Console -> command
php-fpm / console -> PostgreSQL (database:5432)
```

- One DI container for web and CLI; the static parameter `scope` is `web` or `cli`.
- `Contributte\Bootstrap\ExtraConfigurator` sets debug mode from `NETTE_DEBUG` and adds environment variables to
  `parameters` at compile time.
- No queue or worker; everything runs inside a request or a console command.

## Stack

- PHP `>=8.4`; Nette `application` 3.2, `di` 3.2, Latte 3.1, Tracy 2.11 (versions from `composer.lock`)
- Doctrine ORM 3.6 and DBAL 4.4 via `nettrine/orm` `^0.10`, `nettrine/dbal` `^0.10`, `nettrine/migrations` `^0.10`,
  `nettrine/fixtures` `^0.9`, `nettrine/cache` `^0.5`, `nettrine/annotations` `^0.9`
- Symfony Console 7.4 via `contributte/console` and `contributte/console-extra`
- Symfony EventDispatcher via `contributte/event-dispatcher` and `contributte/event-dispatcher-extra`
- Mail, PDF and logging via `contributte/mail`, `contributte/mailing`, `contributte/pdf` (mPDF 8), `contributte/monolog`
- QA: `contributte/qa` `^0.4`, `contributte/phpstan` `^0.3` with `phpstan/phpstan-doctrine`, `contributte/tester` `^0.3`
- Docker images: `thecodingmachine/php:8.4-v4-fpm`, `nginx:alpine`, `dockette/postgres:10`

## Layout

```
app/Bootstrap.php     # boot(), runWeb(), runCli()
app/Console/          # HelloCommand
app/Domain/           # User, Order, Http: entities, queries, facades, events, subscribers
app/Model/            # Database, Security, Router, Latte, Utils, Exception
app/UI/               # Modules (Admin, Base, Front, Mailing, Pdf), Control/, Form/
bin/console           # CLI entry point
config/               # app/, env/, ext/, local.neon.example; local.neon is git-ignored
db/                   # Migrations/, Fixtures/ (namespace Database\)
resources/            # mail/, pdf/ and tracy/ error templates
tests/                # Cases/E2E, Cases/Unit, Toolkit, Fixtures
www/                  # public web root: index.php, assets/
.build/               # phpstan-doctrine.php, boots the container for PHPStan
.docker/              # nginx and php Dockerfiles, nginx config
```

## Configuration

- Load order: `config/env/dev.neon` when `NETTE_ENV=dev`, otherwise `config/env/prod.neon`; both include
  `config/env/base.neon`, which includes `config/app/*.neon` and `config/ext/*.neon`. `config/local.neon` is loaded
  last and wins.
- `config/local.neon` is created by `make init` from `config/local.neon.example`. It holds the database host,
  name, user and password, and optionally SMTP. Driver `pdo_pgsql` and port `5432` are in `config/app/parameters.neon`.
- `NETTE_DEBUG=1` enables Tracy. `NETTE_ENV` selects the environment file. Both are read from the process
  environment, not from a `.env` file.
- `config/env/test.neon` exists but `Bootstrap` never loads it.

## Data Model

- `User` (`app/Domain/User/User.php`) with the `TId`, `TCreatedAt` and `TUpdatedAt` traits from
  `app/Model/Database/Entity/`; roles `admin` and `user`, states fresh, activated, blocked.
- `nettrine.orm` maps `App\Domain` in `app/Domain` with the attribute driver.
- Schema changes only through a new file in `db/Migrations/`; never edit an applied migration. The one migration,
  `Version20190407153346`, creates the `user` table and aborts on anything but PostgreSQL.
- Fixtures in `db/Fixtures/`; `UserFixture` creates `admin@admin.cz` with the password `admin`.

## Services

- `nginx` (host 8080 and 8443), `php` (FPM on 9000, runs `composer install`, migrations and fixtures on start),
  `database` (PostgreSQL, no published port), `adminer` (host 8080).
- `make docker-postgres` starts PostgreSQL 12 on 5432 and `make docker-adminer` starts Adminer on 9999, outside
  Compose.
- Local credentials `contributte` / `contributte`, database `contributte`; for development only.

## Request Flow

- HTTP: `www/index.php` -> `Bootstrap::runWeb()` -> `RouterFactory` (`mailing/...`, `pdf/...`, `admin/...`, then
  Front) -> presenter -> Latte template from the module `templates/` folder.
- Admin presenters extend `SecuredPresenter`; `checkRequirements()` redirects to `:Admin:Sign:in` when signed out
  and to the front home page when `StaticAuthorizator` denies `Admin:Home`.
- Errors: `Front:Error` logs the exception, forwards 4xx to `Front:Error4xx` (template by status code) and prints
  `app/UI/Modules/Base/templates/500.phtml` for the rest. Tracy uses `resources/tracy/500.phtml` in production.
- CLI: `bin/console` -> `Bootstrap::runCli()` -> Symfony Console -> command service.

## Build and Deploy

- `make build` drops the whole database, runs migrations and loads fixtures with `--append`. It destroys data;
  local only.
- `make deploy` runs `clean`, `project`, `build`, `clean`. The demo at `examples.contributte.org/webapp-skeleton/`
  is deployed from `master`.

## Testing

- `tests/Cases/Unit` - plain classes (`Model/Utils/Strings`).
- `tests/Cases/E2E` - booted container: web and CLI entry points build, Doctrine mapping validates, every
  `*.latte` in `app/` compiles. `EntrypointTest` copies `config/local.neon.example` when `local.neon` is missing.
- CI workflows: `codesniffer`, `phpstan`, `tests`, `coverage` (Codecov), `database` (migrations on PostgreSQL 16).
- Not tested: rendered HTML, sign-in, fixtures, mailing, PDF output, templates in `resources/` and JS in `www/assets/`.

## Decisions

### 2026-09-28 Doctrine ORM instead of nette/database

- **Context:** The skeleton must show entities, repositories, migrations and fixtures.
- **Decision:** Use `nettrine/*` for DBAL, ORM, migrations and fixtures.
- **Consequences:** (+) one toolset for schema and data; (-) heavier boot, PHPStan needs `.build/phpstan-doctrine.php`.

### 2026-09-28 Plain CSS and Bootstrap from a CDN

- **Context:** A new developer should run the app without Node.js.
- **Decision:** Keep `www/assets/*.css` and `*.js` as plain files and load Bootstrap 4 from cdnjs.
- **Consequences:** (+) nothing to compile; (-) no bundling, and the pages need network access to look right.

## Known Limits

- `docker-compose.yml` uses `dockette/postgres:10`, which is out of upstream support; `make docker-postgres` uses 12,
  CI uses 16 and `nettrine.dbal` declares `serverVersion: "16.0"`. Align all four.
- `nginx` and `adminer` both publish host port 8080, so `docker compose up` cannot start both. The `database`
  service publishes no port, so `make dev` on the host cannot reach it.
- `docker-compose.yml` still has the obsolete `version:` key, and its data folder `.docker/data/` is not in `.gitignore`.
- `User` and its traits use `@ORM\...` docblock annotations, but Doctrine ORM 3 reads only attributes. Doctrine sees
  no entity, so sign-in and `UserFixture` fail and the mapping test checks nothing until they become `#[ORM\...]`.
- `contributte.mailing` points its layout to `%appDir%/resources/mail/@layout.latte`, which does not exist; the
  file is in `resources/mail/` at the root.
- `OrderLogSubscriber` and `RequestLoggerSubscriber` are not registered in `config/`, so they never run.
- `config/env/test.neon` is never loaded and references `Tests\Toolkit\Nette\DummyUserStorage`; the class is
  `Tests\Fixtures\Dummy\DummyUserStorage`. The `database` workflow sets `NETTE_ENV=test`, which loads `prod.neon`.
- The Makefile has no `help`, `docker-up` or `##@` sections, and `cs` / `csf` call `vendor/bin/codesniffer` and
  `codefixer`. `var/` is not in `.gitignore`.
- Presenters use `@inject` annotations instead of `#[Inject]`, and `contributte/tester` is `^0.3`.
