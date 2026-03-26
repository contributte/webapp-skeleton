# Contributing

Thank you for your interest in contributing to webapp-skeleton!

## Prerequisites

- PHP 8.4+
- [Composer](https://getcomposer.org/)
- PostgreSQL >= 10 (or use Docker)
- Make (optional, but recommended)

## Getting Started

1. Fork the repository and clone your fork:
   ```bash
   git clone https://github.com/YOUR_USERNAME/webapp-skeleton.git
   cd webapp-skeleton
   ```

2. Set up the project:
   ```bash
   cp config/local.neon.example config/local.neon
   make project
   ```

3. Start PostgreSQL (via Docker or locally):
   ```bash
   make docker-postgres
   ```

4. Build the database:
   ```bash
   make build
   ```

5. Start the development server:
   ```bash
   make dev
   ```

6. Open http://localhost:8000 to verify everything works.

See the [Troubleshooting guide](.docs/troubleshooting.md) if you run into issues.

## Development Workflow

1. Create a feature branch from `master`:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes.

3. Run quality checks:
   ```bash
   make qa      # CodeSniffer + PHPStan
   make tests   # Run test suite
   ```

4. Commit your changes with a clear message.

5. Push and open a pull request against `master`.

## Code Quality Requirements

All pull requests must pass:

- **CodeSniffer** (`make cs`) - Code style must conform to the project's ruleset
- **PHPStan level 9** (`make phpstan`) - Static analysis must report no errors
- **Tests** (`make tests`) - All tests must pass

Use `make csf` to auto-fix code style issues.

## Project Structure

See the [Architecture Overview](.docs/architecture.md) for a detailed explanation of the codebase structure and design patterns.

## Additional Documentation

- [Architecture Overview](.docs/architecture.md)
- [Database Guide](.docs/database.md)
- [Testing Guide](.docs/testing.md)
- [Troubleshooting](.docs/troubleshooting.md)

## Questions?

- Open an issue on GitHub
- Visit [contributte.org](https://contributte.org)
- Join the community on [Gitter](https://bit.ly/ctteg) or the [forum](https://bit.ly/cttfo)
