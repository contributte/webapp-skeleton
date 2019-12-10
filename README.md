<h1 align=center>Nutella Project</h1>

<p align=center>
    <strong>Nutella project</strong> is an example project based on Nette Framework and many useful packages by <a href="https://github.com/f3l1x">@f3l1x</a>.
</p>

<p align=center>
    Why <strong>nutella</strong>? Because it's all mixed into 1 awesome piece.
</p>

<p align=center>
🕹 <a href="https://f3l1x.io">f3l1x.io</a> | 💻 <a href="https://github.com/f3l1x">f3l1x</a> | 🐦 <a href="https://twitter.com/xf3l1x">@xf3l1x</a>
</p>

<p align=center>
    <code>composer create-project -s dev planette/nutella-project acme</code>
</p>
<p align=center>
    Take a look at demo <a href="https://examples.planette.io/planette/nutella-project/">examples.planette.io/planette/nutella-project/</a>
</p>

<p align=center>
    <img src="https://raw.githubusercontent.com/planette/nutella-project/master/.docs/assets/screenshot1.png">
</p>

[![Build Status](https://img.shields.io/travis/planette/nutella-project.svg?style=flat-square)](https://travis-ci.org/planette/nutella-project)
[![Join the chat](https://img.shields.io/gitter/room/contributte/contributte.svg?style=flat-square)](http://bit.ly/ctteg)

-----

## Goal

Main goal is to provide best prepared starter-kit project for Nette developers.

Focused on:

- `nette/*` packages
- Doctrine ORM via `nettrine/*`
- Symfony components via `contributte/*`
- codestyle checking via **CodeSniffer** and `ninjify/*`
- static analysing via **phpstan**
- unit / integration tests via **Nette Tester** and `ninjify/*`

## Demo

https://examples.planette.io/planette/nutella-project/

## Install

1) At first, use composer to install this project.

    ```
    composer create-project planette/nutella-project
    ```

2) After that, you have to setup Postgres >= 10 database. You can start it manually or use docker image `postgres:10`.

    ```
    docker run -it -p 5432:5432 -e POSTGRES_PASSWORD=nutella -e POSTGRES_USER=nutella postgres:10
    ```

    Or use make task, `make loc-postgres`.

3) Custom configuration file is located at `app/config/config.local.neon`. Edit it if you want.

    Default configuration should look like:

    ```yaml
    # Host Config
    parameters:

        # Database
        database:
            host: localhost
            dbname: nutella
            user: nutella
            password: nutella
    ```

4) Ok database is now running and application is configured to connect to it. Let's create initial data.

    Run `NETTE_DEBUG=1 bin/console migrations:migrate` to create tables.
    Run `NETTE_DEBUG=1 bin/console doctrine:fixtures:load --append` to create first user(s).

    Or via task `make build`.

5) Start your devstack or use PHP local development server.

    You can start PHP server by running `php -S localhost:8000 -t www` or use prepared make task `make loc-web`.

6) Open http://localhost and enjoy!

    Take a look at:
    - http://localhost:8000.
    - http://localhost:8000/admin (admin@admin.cz / admin)

## Features

Here is a list of all features you can find in this project.

- :package: Packages
    - Nette 3.0
    - Contributte
    - Nettrine
- :deciduous_tree: Structure
    - `app`
        - `config` - configuration files
            - `env` - prod/dev/test environments
            - `app` - application configs
            - `ext` - extensions configs
            - `config.local.neon` - local runtime config
            - `config.local.neon.dist` - template for local config
        - `domain` - business logic and domain specific classes
        - `model` - application backbone
        - `modules` - Front/Admin module, presenters and components
        - `resources` - static content for mails and others
        - `ui` - UI components and base classes
        - `bootstrap.php` - Nette entrypoint
    - `bin` - console entrypoint (`bin/console`)
    - `db` - database files
        - `fixtures` - PHP fixtures
        - `migrations` - migrations files
    - `docs` - documentation
    - `log` - runtime and error logs
    - `temp` - temp files and cache
    - `tests` - test engine and unit/integration tests
    - `vendor` - composer's folder
    - `www` - public content
- :exclamation: Tracy
    - Cool error 500 page

### Composer packages

Take a detailed look :eyes: at each single package.

- [contributte/bootstrap](https://contributte.org/packages/contributte/bootstrap.html)
- [contributte/application](https://contributte.org/packages/contributte/application.html)
- [contributte/di](https://contributte.org/packages/contributte/di.html)
- [contributte/cache](https://contributte.org/packages/contributte/cache.html)
- [contributte/http](https://contributte.org/packages/contributte/http.html)
- [contributte/forms](https://contributte.org/packages/contributte/forms.html)
- [contributte/latte](https://contributte.org/packages/contributte/latte.html)
- [contributte/mail](https://contributte.org/packages/contributte/mail.html)
- [contributte/security](https://contributte.org/packages/contributte/security.html)
- [contributte/utils](https://contributte.org/packages/contributte/utils.html)
- [contributte/tracy](https://contributte.org/packages/contributte/tracy.html)
- [contributte/console](https://contributte.org/packages/contributte/console.html)
- [contributte/event-dispatcher](https://contributte.org/packages/contributte/event-dispatcher.html)
- [contributte/event-dispatcher-extra](https://contributte.org/packages/contributte/event-dispatcher-extra.html)
- [contributte/neonizer](https://contributte.org/packages/contributte/neonizer.html)
- [contributte/mailing](https://contributte.org/packages/contributte/mailing.html)
- [contributte/monolog](https://contributte.org/packages/contributte/monolog.html)

**Nettrine**

- [nettrine/orm](https://contributte.org/packages/nettrine/orm.html)
- [nettrine/dbal](https://contributte.org/packages/nettrine/dbal.html)
- [nettrine/migrations](https://contributte.org/packages/nettrine/migrations.html)
- [nettrine/fixtures](https://contributte.org/packages/nettrine/fixtures.html)
- [nettrine/extensions](https://contributte.org/packages/nettrine/extensions.html)

**Nette**

- [nette/finder](https://github.com/nette/finder)
- [nette/safe-stream](https://github.com/nette/safe-stream)
- [nette/robot-loader](https://github.com/nette/robot-loader)

## Demo

![](.docs/assets/screenshot1.png)

> admin@admin.cz / admin

![](.docs/assets/screenshot2.png)
![](.docs/assets/screenshot3.png)
![](.docs/assets/screenshot4.png)
