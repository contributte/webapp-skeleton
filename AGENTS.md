# Webapp Skeleton

Full featured web application starter built on Nette Framework, Contributte and Nettrine (Doctrine).

## Stack

- Language: PHP >=8.4
- Framework: Nette 3.2, plus Contributte packages and Nettrine (Doctrine ORM 3, DBAL, Migrations)
- Tests: Nette Tester; static analysis: PHPStan (level 9); code style: Contributte coding standard

## Development

```bash
make install     # install dependencies
make qa          # PHPStan and code style
make csf         # fix code style
make tests       # run all tests
vendor/bin/tester -s -p php --colors 1 -C tests/Cases/Unit/Model/Utils/Strings.phpt   # run one test file
make coverage    # code coverage
```

All targets are defined in the `Makefile`. `make project` installs dependencies and creates `var/` folders, `make init` creates the local config, `make dev` starts the dev server on http://localhost:8000, `make build` rebuilds the database with migrations and fixtures, `make docker-postgres` and `make docker-adminer` start helper containers.

## Principles

- KISS: write the simplest code that works; no speculative abstractions.
- DRY: one source of truth; reuse existing code before adding new.
- YAGNI: build what is needed now, not what might be needed later.
- Small, final classes with typed properties and `declare(strict_types = 1)`.
- Every change comes with a test; `make qa tests` must pass before a commit.
