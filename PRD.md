# Webapp Skeleton PRD

Webapp Skeleton is a Nette Framework starter project with Doctrine ORM on PostgreSQL, a front module, an admin
module with sign-in, console, mailing, PDF output and event examples, set up for Docker and CI.

## Problem

Starting a Nette application means choosing and wiring about 25 packages: DI extensions, Doctrine, migrations,
fixtures, console, logging, mail, PDF, tests and QA. Each new project repeats that work and repeats the same
config mistakes. A copy of a working application shows the wiring faster than the documentation of each package.

## Users

- Developers who know PHP and basic Nette and start a new web application with `composer create-project`.
- Maintainers of Contributte and Nettrine packages who need a real application to try an integration in.
- Visitors of the demo at `examples.contributte.org/webapp-skeleton/` who want to see the result before installing.

## Goals

- `composer create-project` plus `make init` and `make dev` or `docker compose up` gives a running application.
- Every bundled package is configured in `config/` and used at least once in `app/`, so the wiring is shown, not
  described.
- `make qa` and `make tests` pass on a fresh copy.
- The admin module shows sign-in, sign-out and a secured page with a role check.
- The code is small enough to read in one sitting and delete what you don't need.

## Non-goals

- Not a CMS or admin generator, because the admin module is a sign-in example, not a product.
- No frontend build pipeline, because `www/assets/` holds plain CSS and JS and Bootstrap comes from a CDN.
- No REST API, queue or multi-tenant setup, because each would double the code a new developer has to read.
- No production hosting setup, because the Docker stack runs with `NETTE_DEBUG=1` and local credentials.

## Scope

Front:

- Home page with navigation to Home, Admin and the PDF example.
- Error pages: 403, 404, 405, 410, a generic 4xx page, and a static 500 page for production.

Admin:

- As a developer, I can sign in at `/admin/sign/in` with the fixture user `admin@admin.cz` / `admin`.
- As a signed-in admin, I can open the secured home page, send the order form and sign out.
- A user without the `admin` role is redirected to the front home page.

Domain and database:

- `User` entity, `UserRepository`, `UserQuery`, `CreateUserFacade` and `UserFixture`.
- One PostgreSQL migration that creates the `user` table.
- `OrderCreated` event dispatched from the admin order form, with `OrderLogSubscriber` and
  `RequestLoggerSubscriber` as subscriber examples.

Console:

- `bin/console` with `HelloCommand` (`hello`) and the Doctrine, migrations and fixtures commands.

Mailing and PDF:

- `/mailing` sends a templated e-mail through `contributte/mailing`.
- `/pdf` renders `resources/pdf/example.latte` as a PDF, inline or as a download, through `contributte/pdf`.

QA:

- CodeSniffer through `contributte/qa`, PHPStan level 9 with the Doctrine extension, Nette Tester with E2E tests
  for the container, the Doctrine mapping and Latte compilation.
- GitHub Actions workflows `codesniffer`, `phpstan`, `tests`, `coverage` and `database`.

## Success Criteria

- `make project init` followed by `make tests` passes on PHP 8.4 without a running database.
- `make dev` serves the front home page at `http://localhost:8000` and the sign-in page at
  `http://localhost:8000/admin`.
- `make build` against the Docker or a local PostgreSQL creates the `user` table and the fixture user.
- CI runs the code style, PHPStan, tests, coverage and database workflows green on `master`.
- The demo at `examples.contributte.org/webapp-skeleton/` runs the current `master`.

Today a fresh copy does not meet all of these; the gaps are listed in `TECH.md` under Known Limits.

## Out of Scope

- Per-package usage and configuration, see each package's `.docs/README.md` on contributte.org.
- Doctrine setup without the UI, see `contributte/doctrine-skeleton`.
- Messenger and queues, see `contributte/messenger-skeleton`.
- More small examples of single packages, see `contributte/playground`.

## Open Questions

- 2026-09-28: Should the `/mailing` example send on GET, or move behind a form in the admin module?
- 2026-09-28: Should the `docker-compose.yml` stack replace `make docker-postgres` and `make docker-adminer`, or
  should both stay?
