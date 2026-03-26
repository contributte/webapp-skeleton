# Testing Guide

## Overview

The project uses [Nette Tester](https://tester.nette.org/) as its testing framework, with [Contributte Tester](https://contributte.org/packages/contributte/tester.html) providing additional utilities.

## Test Structure

```
tests/
├── bootstrap.php              # Test bootstrap (sets up environment)
├── Toolkit/
│   ├── BaseTestCase.php       # Base class for test cases
│   └── Tests.php              # Path constants (ROOT_PATH, APP_PATH)
├── Fixtures/
│   └── Dummy/
│       └── DummyUserStorage.php   # Test doubles
└── Cases/
    ├── Unit/
    │   └── Model/Utils/
    │       └── Strings.phpt       # Unit tests
    └── E2E/
        ├── Container/
        │   └── EntrypointTest.php # DI container boot test
        ├── Database/
        │   └── MappingTest.phpt   # Doctrine mapping validation
        └── Latte/
            └── CompilerTest.phpt  # Latte template compilation test
```

## Running Tests

| Command | Description |
|---------|-------------|
| `make tests` | Run all tests |
| `make coverage` | Run tests with XML coverage report |
| `make qa` | Run CodeSniffer + PHPStan (no tests) |

### Running Individual Tests

```bash
vendor/bin/tester -s -p php tests/Cases/Unit/Model/Utils/Strings.phpt
```

Flags:
- `-s` - Show information about skipped tests
- `-p php` - Path to PHP binary
- `--colors 1` - Force colored output
- `-C` - Use system-wide php.ini

## Writing Tests

### Nette Tester Tests (`.phpt` files)

Most tests use the `.phpt` extension and Nette Tester assertions:

```php
<?php declare(strict_types = 1);

use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Test
Assert::equal('expected', 'actual');
Assert::true(someCondition());
Assert::exception(function () {
    throw new RuntimeException();
}, RuntimeException::class);
```

### TestCase-based Tests (`.php` files)

For grouped tests, extend `BaseTestCase`:

```php
<?php declare(strict_types = 1);

namespace Tests\Cases\E2E\Container;

use Tests\Toolkit\BaseTestCase;
use Tester\Assert;

class EntrypointTest extends BaseTestCase
{
    public function testWebEntrypoint(): void
    {
        // test logic
        Assert::true($condition);
    }
}

$test = new EntrypointTest();
$test->run();
```

### Test Fixtures

Place test doubles and dummy implementations in `tests/Fixtures/`. Example: `DummyUserStorage` provides a fake user storage for tests that don't need real authentication.

### Path Constants

Use `Tests\Toolkit\Tests` for consistent paths:

```php
use Tests\Toolkit\Tests;

$rootPath = Tests::ROOT_PATH;  // project root
$appPath = Tests::APP_PATH;    // app/ directory
```

## Static Analysis

### PHPStan

Configuration: `phpstan.neon`
- **Level:** 9 (strictest)
- **PHP version:** 8.4
- **Paths analyzed:** `app`, `bin`
- **Includes:** Doctrine extension for ORM-aware analysis

```bash
make phpstan
# or directly:
vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=512M
```

### CodeSniffer

Configuration: `ruleset.xml`
- **Base ruleset:** `contributte/qa` ruleset for PHP 8.4
- **Namespace mapping:** `app` -> `App\`, `tests` -> `Tests\`

```bash
make cs     # check code style
make csf    # auto-fix code style issues
```

## CI/CD Workflows

The project has 5 GitHub Actions workflows in `.github/workflows/`:

| Workflow | File | Trigger | Description |
|----------|------|---------|-------------|
| Tests | `tests.yml` | Push/PR | Runs Nette Tester with PHP 8.4 |
| Database | `database.yml` | Push/PR | Tests migrations against PostgreSQL 16 |
| PHPStan | `phpstan.yml` | Push/PR | Static analysis at level 9 |
| CodeSniffer | `codesniffer.yml` | Push/PR | Code style checking |
| Coverage | `coverage.yml` | Push/PR | Code coverage reporting |

All checks must pass before merging pull requests.
