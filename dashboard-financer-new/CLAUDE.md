# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 12 financial dashboard application with Vite + Tailwind CSS 4, containerized with Docker (PHP 8.3, MySQL 8.0, Redis, Nginx).

## Commands

### Docker Setup (primary dev environment)

```sh
# Start containers
docker compose up -d

# Access app container shell
docker compose exec app bash

# Inside container: install dependencies and generate key
composer install
php artisan key:generate
php artisan migrate
```

### Development (inside container or with local PHP)

```sh
# Run all dev processes (server + queue + logs + Vite HMR) concurrently
composer dev

# Or individually:
php artisan serve
npm run dev
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0   # log viewer
```

### Build & Setup

```sh
# Full project setup from scratch
composer setup   # installs deps, copies .env, generates key, migrates, builds frontend

# Frontend build only
npm run build
```

### Testing

```sh
# Run all tests (clears config cache first)
composer test

# Run a single test file
php artisan test tests/Feature/ExampleTest.php

# Run a specific test method
php artisan test --filter=test_method_name
```

### Code Style

```sh
# Fix PHP code style (Laravel Pint)
./vendor/bin/pint
```

## Environment Variables

Key `.env` values for Docker setup (use `DB_HOST=db`, `REDIS_HOST=redis`):

```
APP_URL=http://localhost:8989
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```

## Service Ports

| Service     | Port |
|-------------|------|
| App (Nginx) | 8989 |
| PHPMyAdmin  | 8080 |
| MySQL       | 3388 |

## Architecture

Standard Laravel MVC structure:
- `app/Http/Controllers/` — request handling
- `app/Models/` — Eloquent models
- `resources/views/` — Blade templates
- `resources/js/` — JS entry (`app.js` imports `bootstrap.js` which configures Axios)
- `resources/css/app.css` — Tailwind CSS 4 entry point
- `routes/web.php` — web routes
- `database/migrations/` — schema migrations
