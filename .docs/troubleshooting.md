# Troubleshooting

## Common Errors

### "Connection refused" or "could not connect to server"

PostgreSQL is not running or the connection settings are wrong.

1. Verify PostgreSQL is running: `pg_isready` or check Docker with `docker ps`
2. Check `config/local.neon` - ensure `host`, `port`, `user`, and `password` match your setup
3. When using Docker: the host should be `0.0.0.0` or `localhost` (for `make docker-postgres`), or `database` (for `docker-compose`)

### "Table not found" or "relation does not exist"

The database schema hasn't been created yet.

```bash
NETTE_DEBUG=1 bin/console migrations:migrate --no-interaction
NETTE_DEBUG=1 bin/console doctrine:fixtures:load --no-interaction --append
```

Or simply: `make build`

### "Could not find driver"

The `pdo_pgsql` PHP extension is not installed or enabled.

- **Linux (apt):** `sudo apt install php-pgsql`
- **Linux (yum):** `sudo yum install php-pgsql`
- **macOS (Homebrew):** Typically included; check with `php -m | grep pgsql`
- **Windows:** Uncomment `extension=pdo_pgsql` in your `php.ini`
- **Docker:** Already configured in the provided docker-compose setup

### Port 8080 conflict with docker-compose

Both `nginx` and `adminer` services in `docker-compose.yml` are mapped to host port 8080. Change one of them:

```yaml
# Change adminer to a different port, e.g.:
adminer:
    image: dockette/adminer:dg
    ports:
      - 9090:80  # was 8080:80
```

Note: When using `make docker-adminer` separately, Adminer runs on port 9999 (no conflict).

## Windows-Specific Issues

### Makefile commands don't work

The `Makefile` uses Unix shell commands (`find`, `chmod`). On Windows:

- **Recommended:** Use [WSL2](https://learn.microsoft.com/en-us/windows/wsl/) (Windows Subsystem for Linux)
- **Alternative:** Use Git Bash (included with [Git for Windows](https://gitforwindows.org/))
- **Alternative:** Run commands manually:
  ```cmd
  composer install
  mkdir var\tmp var\log
  php -S 0.0.0.0:8000 -t www
  ```

### Docker performance is slow

When using Docker Desktop on Windows, ensure you are using the **WSL2 backend** (not Hyper-V). Store the project files inside the WSL filesystem (`/home/...`) rather than the Windows filesystem (`/mnt/c/...`) for much better I/O performance.

### Path issues with fixtures

On Windows, use forward slashes in configuration paths. If you encounter path-related errors with fixtures or migrations, verify the paths in `config/ext/nettrine.neon` use the `%rootDir%` parameter.

## Required PHP Extensions

The following extensions are required (all included in the Docker setup):

| Extension | Purpose |
|-----------|---------|
| `pdo_pgsql` | PostgreSQL database driver |
| `intl` | Internationalization (string functions) |
| `gd` | Image processing |

Check your extensions with: `php -m`

## Switching to MySQL

To use MySQL instead of PostgreSQL:

1. Edit `config/app/parameters.neon`:
   ```neon
   parameters:
       database:
           driver: pdo_mysql
           port: 3306
   ```

2. Update `config/local.neon` with MySQL credentials

3. Enable `pdo_mysql` extension in your PHP configuration

4. Adjust `docker-compose.yml` or use a MySQL Docker container instead of PostgreSQL

5. Re-run migrations: `make build`

Note: Some SQL syntax differences may require migration adjustments.
