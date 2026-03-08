# Makefile para facilitar comandos do projeto

.PHONY: help

help: ## Mostra esta mensagem de ajuda
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

install: ## Instala o projeto Yii2
	docker-compose up -d
	docker exec -it yii_php composer create-project --prefer-dist yiisoft/yii2-app-advanced .
	@echo "\n✅ Projeto instalado! Execute 'make init' para inicializar."

init: ## Inicializa o projeto Yii2 (development)
	docker exec -it yii_php php init --env=Development --overwrite=All
	@echo "\n✅ Projeto inicializado! Configure o banco de dados e execute 'make migrate'."

up: ## Inicia os containers
	docker-compose up -d
	@echo "\n✅ Containers iniciados!"
	@echo "Frontend: http://localhost:8080"
	@echo "phpMyAdmin: http://localhost:8081"

down: ## Para os containers
	docker-compose down

restart: ## Reinicia os containers
	docker-compose restart

logs: ## Mostra os logs dos containers
	docker-compose logs -f

bash: ## Acessa o bash do container PHP
	docker exec -it yii_php bash

migrate: ## Executa as migrations do banco de dados
	docker exec -it yii_php php yii migrate --interactive=0

migrate-create: ## Cria uma nova migration (use name=nome_da_migration)
	docker exec -it yii_php php yii migrate/create $(name)

composer-install: ## Instala dependências do Composer
	docker exec -it yii_php composer install

composer-update: ## Atualiza dependências do Composer
	docker exec -it yii_php composer update

cache-flush: ## Limpa o cache do Yii
	docker exec -it yii_php php yii cache/flush-all

fix-permissions: ## Corrige permissões dos arquivos
	docker exec -it yii_php chown -R www-data:www-data /var/www/html
	docker exec -it yii_php chmod -R 755 /var/www/html

clean: ## Remove containers, volumes e imagens
	docker-compose down -v
	docker system prune -f

db-backup: ## Faz backup do banco de dados
	docker exec yii_mysql mysqldump -u yii_user -pyii_password yii_db > backup_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "\n✅ Backup criado!"

db-restore: ## Restaura o banco de dados (use file=backup.sql)
	docker exec -i yii_mysql mysql -u yii_user -pyii_password yii_db < $(file)
	@echo "\n✅ Banco de dados restaurado!"
