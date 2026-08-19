# Architecture Overview

This document describes the architecture of the webapp-skeleton project.

## Layered Architecture

```mermaid
graph TD
    A[www/index.php] --> B[app/Bootstrap.php]
    B --> C[Nette DI Container]
    C --> D["UI Layer<br/>(app/UI/Modules)<br/>Presenters + Templates"]
    C --> E["Domain Layer<br/>(app/Domain)<br/>Entities + Events"]
    C --> F["Model Layer<br/>(app/Model)<br/>Database + Security + Router"]
```

- **UI Layer** (`app/UI/`) - Presenters, templates (Latte), forms, and reusable controls
- **Domain Layer** (`app/Domain/`) - Business entities, repositories, facades, and domain events
- **Model Layer** (`app/Model/`) - Infrastructure: database abstractions, security, routing, utilities

## Module System

The application is organized into 4 modules, each with its own route prefix:

| Module | URL prefix | Namespace | Purpose |
|--------|-----------|-----------|---------|
| Front | `/` | `App\UI\Modules\Front` | Public-facing pages |
| Admin | `/admin` | `App\UI\Modules\Admin` | Admin dashboard (secured) |
| Mailing | `/mailing` | `App\UI\Modules\Mailing` | Email template preview |
| Pdf | `/pdf` | `App\UI\Modules\Pdf` | PDF generation examples |

Module mapping is defined in `config/env/base.neon`:

```neon
application:
    mapping:
        Admin: [App\UI\Modules\Admin, *, *\*Presenter]
        Front: [App\UI\Modules\Front, *, *\*Presenter]
        Mailing: [App\UI\Modules\Mailing, *, *\*Presenter]
        Pdf: [App\UI\Modules\Pdf, *, *\*Presenter]
```

Routing is defined in `app/Model/Router/RouterFactory.php`. Each module gets its own `RouteList` with a URL prefix (e.g., `admin/<presenter>/<action>[/<id>]`). The Front module catches all remaining routes.

## Presenter Hierarchy

```mermaid
classDiagram
    class Presenter["Nette\\Application\\UI\\Presenter"]
    class BasePresenter {
        Uses: StructuredTemplates, TFlashMessage, TModuleUtils
        $template : TemplateProperty
        $user : SecurityUser
    }
    class SecuredPresenter {
        +checkRequirements() checks auth
        Redirects to sign-in if not logged in
    }
    class UnsecuredPresenter {
        Redirects to homepage if already logged in
    }
    class BaseAdminPresenter {
        Checks admin role permission
    }
    class BaseFrontPresenter

    Presenter <|-- BasePresenter
    BasePresenter <|-- SecuredPresenter
    BasePresenter <|-- UnsecuredPresenter
    BasePresenter <|-- BaseFrontPresenter
    SecuredPresenter <|-- BaseAdminPresenter
    BaseAdminPresenter <|-- AdminHomePresenter["Admin\\HomePresenter"]
    BaseAdminPresenter <|-- AdminSignPresenter["Admin\\SignPresenter"]
    BaseFrontPresenter <|-- FrontHomePresenter["Front\\HomePresenter"]
    BaseFrontPresenter <|-- FrontError4xxPresenter["Front\\Error4xxPresenter"]
```

Each module also has its own `@layout.latte` template that extends the base layout.

## Domain Layer Patterns

### Entities

Entities live in `app/Domain/{Aggregate}/` and use Doctrine ORM PHP 8 attributes for mapping. Common fields are provided by composable traits:

- `TId` - Auto-increment integer primary key
- `TCreatedAt` - Timestamp set on `PrePersist`
- `TUpdatedAt` - Timestamp set on `PreUpdate`

Example: `App\Domain\User\User` uses all three traits and defines fields like name, email, password hash, role, and state.

### Repository Pattern

Each entity has a repository extending `AbstractRepository` (which extends Doctrine's `EntityRepository`). The repository class is linked to the entity via the `#[ORM\Entity(repositoryClass: ...)]` attribute.

Example: `UserRepository` provides `findOneByEmail()`.

### Query Object Pattern

For complex queries, the skeleton uses a Query Object pattern:

- `AbstractQuery` holds an array of `$ons` callbacks (query modifiers)
- Static factory methods create pre-configured query objects
- `QueryManager` executes queries via Doctrine's `EntityManager`

```php
// Usage example:
$user = $this->queryManager->findOne(UserQuery::ofEmail('admin@admin.cz'));
```

### Event System

Uses Symfony EventDispatcher. Domain events are plain classes (e.g., `OrderCreated`). Subscribers (e.g., `OrderLogSubscriber`) listen to events with configurable priority using `getSubscribedEvents()`.

`RequestLoggerSubscriber` demonstrates listening to Nette application events for HTTP request logging.

## Configuration System

Configuration uses the NEON format with a layered approach:

```mermaid
graph TD
    BASE["config/env/base.neon<br/><i>Core config</i>"]
    PARAMS["config/app/parameters.neon<br/><i>Database, SMTP, paths</i>"]
    SERVICES["config/app/services.neon<br/><i>Service definitions</i>"]
    CONTRIB["config/ext/contributte.neon<br/><i>Console, events, monolog, mailing</i>"]
    NETTRINE["config/ext/nettrine.neon<br/><i>Doctrine ORM, DBAL, migrations, fixtures</i>"]

    DEV["config/env/dev.neon<br/><i>Development overrides</i>"]
    PROD["config/env/prod.neon<br/><i>Production overrides</i>"]
    TEST["config/env/test.neon<br/><i>Test overrides</i>"]
    LOCAL["config/local.neon<br/><i>Machine-specific (gitignored)</i>"]

    BASE --> PARAMS
    BASE --> SERVICES
    BASE --> CONTRIB
    BASE --> NETTRINE

    DEV -->|includes| BASE
    PROD -->|includes| BASE
    TEST -->|includes| BASE

    LOCAL -.->|loaded last by Bootstrap| DEV
    LOCAL -.->|loaded last by Bootstrap| PROD
```

`Bootstrap.php` selects the environment config based on the `NETTE_ENV` environment variable (`dev` or `prod`), then loads `config/local.neon` on top.

## Security System

### Authentication Flow

```mermaid
sequenceDiagram
    participant U as User
    participant S as SignPresenter
    participant SU as SecurityUser
    participant A as UserAuthenticator
    participant Q as QueryManager
    participant P as Passwords
    participant DB as Database

    U->>S: Submit login form
    S->>SU: login(email, password)
    SU->>A: authenticate(email, password)
    A->>Q: findOne(UserQuery::ofEmail())
    Q->>DB: SELECT user
    DB-->>A: User entity (or null)
    A->>A: Check activation status
    A->>P: verify(password, hash)
    P-->>A: true/false
    A->>DB: Update lastLoggedAt
    A-->>SU: Identity (role + user data)
    SU-->>S: Logged in
    S-->>U: Redirect to admin dashboard
```

### Authorization

- `StaticAuthorizator` implements role-based access control
- `SecuredPresenter` checks `$user->isLoggedIn()` in `checkRequirements()`
- `BaseAdminPresenter` additionally checks `$user->isAdmin()`

### Roles and States

User entity defines:
- Roles: `ROLE_ADMIN`, `ROLE_USER`
- States: `STATE_FRESH`, `STATE_ACTIVATED`, `STATE_BLOCKED`

## Template Customization

The custom `TemplateFactory` (`app/Model/Latte/TemplateFactory.php`) modifies default Latte template variables:

- Removes default `$user` variable (to prevent misuse with raw Nette user object)
- Adds `$_user` - the `SecurityUser` instance
- Adds `$_template` - reference to the template itself
- Adds `$_filters` - `FilterExecutor` for calling Latte filters from PHP

## Entry Points

- **Web:** `www/index.php` calls `Bootstrap::runWeb()`, which creates the DI container and runs `Nette\Application\Application`
- **CLI:** `bin/console` calls `Bootstrap::runCli()`, which creates the DI container and runs `Symfony\Console\Application`

Both share the same bootstrap and configuration, differing only in the `scope` parameter (`web` vs `cli`).
