# Projeto Yii2 com Docker

Este é um projeto Yii2 Advanced Template configurado com Docker.

## 📋 Requisitos

- Docker
- Docker Compose

## 🚀 Como Iniciar

### 1. Subir os containers Docker

```bash
docker-compose up -d
```

### 2. Instalar o Yii2 Advanced Template

Entre no container PHP:

```bash
docker exec -it yii_php bash
```

Dentro do container, instale o Yii2 Advanced Template:

```bash
composer create-project --prefer-dist yiisoft/yii2-app-advanced .
```

### 3. Inicializar o projeto Yii

Ainda dentro do container, execute:

```bash
php init
```

Escolha a opção `0` para Development.

### 4. Configurar o banco de dados

Edite o arquivo `/app/common/config/main-local.php` e configure a conexão com o banco:

```php
'db' => [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=db;dbname=yii_db',
    'username' => 'yii_user',
    'password' => 'yii_password',
    'charset' => 'utf8',
],
```

### 5. Executar as migrations

```bash
php yii migrate
```

### 6. Sair do container

```bash
exit
```

## 🌐 Acessar a aplicação

- **Frontend**: http://localhost:8080
- **Backend**: http://localhost:8080 (configurar nginx se necessário)
- **phpMyAdmin**: http://localhost:8081

### Credenciais do phpMyAdmin
- **Servidor**: db
- **Usuário**: yii_user
- **Senha**: yii_password

ou

- **Usuário**: root
- **Senha**: root

## 📦 Comandos Úteis

### Parar os containers
```bash
docker-compose down
```

### Ver logs
```bash
docker-compose logs -f
```

### Entrar no container PHP
```bash
docker exec -it yii_php bash
```

### Executar comandos Yii
```bash
docker exec -it yii_php php yii <comando>
```

### Reinstalar dependências
```bash
docker exec -it yii_php composer install
```

## 🗂️ Estrutura do Projeto

```
.
├── app/                    # Aplicação Yii2
│   ├── backend/           # Painel administrativo
│   ├── frontend/          # Aplicação frontend
│   ├── common/            # Código compartilhado
│   ├── console/           # Comandos de console
│   └── vendor/            # Dependências do Composer
├── docker/
│   ├── nginx/             # Configuração do Nginx
│   └── php/               # Dockerfile e configurações PHP
└── docker-compose.yml     # Orquestração dos containers
```

## 🔧 Configurações

### PHP
- Versão: 8.2
- Extensões: PDO MySQL, mbstring, GD, Zip, Intl, etc.
- Configurações em: `docker/php/php.ini`

### MySQL
- Versão: 8.0
- Database: yii_db
- Usuário: yii_user
- Senha: yii_password

### Nginx
- Porta: 8080
- Configuração em: `docker/nginx/default.conf`

## 📝 Notas

- Os dados do MySQL são persistidos no volume `db_data`
- O código da aplicação está mapeado no diretório `./app`
- Para produção, ajuste as configurações de segurança e remova o phpMyAdmin

## 🐛 Troubleshooting

### Erro de permissão
```bash
docker exec -it yii_php chown -R www-data:www-data /var/www/html
docker exec -it yii_php chmod -R 755 /var/www/html
```

### Limpar cache do Yii
```bash
docker exec -it yii_php php yii cache/flush-all
```
