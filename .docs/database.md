# Database Guide

## Requirements

- **PostgreSQL** >= 10 (default driver: `pdo_pgsql`)
- PHP extension: `pdo_pgsql`

## Configuration

Database settings flow through three layers:

1. **`config/app/parameters.neon`** - Default driver and port:
   ```neon
   parameters:
       database:
           driver: pdo_pgsql
           port: 5432
   ```

2. **`config/local.neon`** - Machine-specific host, dbname, user, password (gitignored):
   ```neon
   parameters:
       database:
           host: 0.0.0.0
           dbname: contributte
           user: contributte
           password: contributte
   ```

3. **`config/ext/nettrine.neon`** - Wires parameters into Doctrine DBAL:
   ```neon
   nettrine.dbal:
       connections:
           default:
               driver: %database.driver%
               host: %database.host%
               # ... etc
   ```

Copy `config/local.neon.example` to `config/local.neon` and adjust for your environment.

## Entities

Entities live in `app/Domain/{Aggregate}/` and use Doctrine ORM PHP 8 attributes.

### Entity Traits

Common fields are provided by traits in `app/Model/Database/Entity/`:

| Trait | Fields | Lifecycle |
|-------|--------|-----------|
| `TId` | `int $id` (auto-increment PK) | - |
| `TCreatedAt` | `DateTimeImmutable $createdAt` | Set on `PrePersist` |
| `TUpdatedAt` | `DateTimeImmutable $updatedAt` | Set on `PreUpdate` |

### Creating a New Entity

1. Create a class in `app/Domain/YourAggregate/`:
   ```php
   #[ORM\Entity(repositoryClass: YourRepository::class)]
   #[ORM\Table(name: 'your_table')]
   #[ORM\HasLifecycleCallbacks]
   class YourEntity extends AbstractEntity
   {
       use TId;
       use TCreatedAt;
       use TUpdatedAt;

       #[ORM\Column(type: 'string')]
       private string $name;

       // constructor, getters, setters...
   }
   ```

2. Create a repository in the same directory:
   ```php
   class YourRepository extends AbstractRepository
   {
   }
   ```

3. Entity mapping is auto-discovered from `app/Domain/` (configured in `nettrine.neon`).

## Migrations

Migrations are managed by Doctrine Migrations and stored in `db/Migrations/`.

| Command | Description |
|---------|-------------|
| `bin/console migrations:diff` | Generate migration from entity changes |
| `bin/console migrations:migrate` | Run pending migrations |
| `bin/console migrations:status` | Show migration status |
| `bin/console orm:schema-tool:drop --force` | Drop all tables |

The migration tracking table is named `doctrine_migrations` (configured in `nettrine.neon`).

Prefix commands with `NETTE_DEBUG=1` for development, or use `make build` which runs drop + migrate + fixtures.

## Fixtures

Fixtures seed the database with initial/test data. They live in `db/Fixtures/`.

### Writing a Fixture

Extend `AbstractFixture` and implement `load()`:

```php
class UserFixture extends AbstractFixture
{
    public function getOrder(): int
    {
        return 1; // execution order
    }

    public function load(ObjectManager $manager): void
    {
        $entity = new User('John', 'Doe', 'john@example.com', 'johndoe', $hashedPassword);
        $manager->persist($entity);
        $manager->flush();
    }
}
```

### Loading Fixtures

```bash
bin/console doctrine:fixtures:load --append     # append to existing data
bin/console doctrine:fixtures:load              # purge first, then load
```

Or use `make build` which drops the schema, runs migrations, and loads fixtures.

## Query Object Pattern

For complex queries, use the Query Object pattern instead of writing DQL directly.

### Creating a Query

```php
class UserQuery extends AbstractQuery
{
    public static function ofEmail(string $email): static
    {
        $q = new static();
        $q->ons[] = static function (QueryBuilder $qb) use ($email): QueryBuilder {
            return $qb
                ->select('u')
                ->from(User::class, 'u')
                ->where('u.email = :email')
                ->setParameter('email', $email);
        };
        return $q;
    }
}
```

### Executing a Query

Use `QueryManager` to execute query objects:

```php
// Find one result (or null)
$user = $this->queryManager->findOne(UserQuery::ofEmail('admin@admin.cz'));

// Find all results
$users = $this->queryManager->findAll($someQuery);
```

The `QueryManager` is injected via DI and delegates to Doctrine's `EntityManager`.
