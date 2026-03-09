# Banco — Dashboard Financeiro

Aplicação de dashboard financeiro construída com **Laravel 12**, **Vue 3** e **Tailwind CSS 4**, containerizada com Docker.

## Stack

| Camada      | Tecnologia                          |
|-------------|-------------------------------------|
| Backend     | Laravel 12 / PHP 8.3                |
| Frontend    | Vue 3 + Vue Router + Lucide Icons   |
| Estilização | Tailwind CSS 4                      |
| Build       | Vite 7                              |
| Banco       | PostgreSQL 16                       |
| Cache/Fila  | Redis                               |
| Servidor    | Nginx (Alpine)                      |

## Pré-requisitos

- [Docker](https://www.docker.com/) e Docker Compose
- [Node.js](https://nodejs.org/) (para desenvolvimento local sem Docker)

## Instalação

### 1. Clone o repositório

```sh
git clone <url-do-repositorio>
cd Banco
```

### 2. Configure as variáveis de ambiente

```sh
cp .env.example .env
```

Ajuste os valores no `.env` para o ambiente Docker:

```env
APP_URL=http://localhost:8989

DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=laravel
DB_USERNAME=username
DB_PASSWORD=userpass

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
REDIS_HOST=redis
```

### 3. Suba os containers

```sh
docker compose up -d
```

### 4. Configure a aplicação

```sh
docker compose exec app bash

# Dentro do container:
composer install
php artisan key:generate
php artisan migrate
```

## Desenvolvimento

Execute todos os processos em paralelo (servidor, fila, logs e Vite HMR):

```sh
composer dev
```

Ou individualmente:

```sh
php artisan serve                               # servidor HTTP
npm run dev                                    # Vite HMR
php artisan queue:listen --tries=1 --timeout=0 # fila
php artisan pail --timeout=0                   # visualizador de logs
```

## Setup Completo (alternativo ao Docker)

Para instalar tudo de uma vez sem Docker:

```sh
composer setup   # instala deps, copia .env, gera key, migra e faz build do frontend
```

## Serviços e Portas

| Serviço    | URL / Endereço            |
|------------|---------------------------|
| Aplicação  | http://localhost:8989      |
| pgAdmin    | http://localhost:8080      |
| PostgreSQL | localhost:5433             |

**Credenciais pgAdmin:** `admin@admin.com` / senha definida em `DB_PASSWORD`

## Testes

```sh
# Todos os testes
composer test

# Arquivo específico
php artisan test tests/Feature/ExampleTest.php

# Método específico
php artisan test --filter=nome_do_teste
```

## Code Style

O projeto usa [Laravel Pint](https://laravel.com/docs/pint) para padronização do código PHP:

```sh
./vendor/bin/pint
```

## Estrutura do Projeto

```
app/
├── Http/Controllers/    # Controllers
└── Models/              # Models Eloquent

resources/
├── views/               # Templates Blade
├── js/                  # Entrypoint Vue (app.js)
└── css/app.css          # Entrypoint Tailwind CSS 4

routes/
└── web.php              # Rotas web

database/
└── migrations/          # Migrations
```