.PHONY: install
install:
	@make up
	docker compose exec app composer install
	docker compose exec app php artisan storage:link
	docker compose exec app chmod -R 777 storage bootstrap/cache
	@make migrate

.PHONY: build
build:
	docker compose build

.PHONY: up
up:
	docker compose up -d

.PHONY: down
down:
	docker compose down --remove-orphans

.PHONY: destroy
destroy:
	docker compose down --rmi all --volumes --remove-orphans

.PHONY: restart
restart:
	@make down
	@make up

.PHONY: remake
remake:
	@make destroy
	@make install

.PHONY: ps
ps:
	docker compose ps

.PHONY: app
app:
	docker compose exec app bash

.PHONY: migrate
migrate:
	docker compose exec app php artisan migrate:fresh --seed

.PHONY: tinker
tinker:
	docker compose exec app php artisan tinker

.PHONY: ide-helper
ide-helper:
	docker compose exec app php artisan clear-compiled
	docker compose exec app php artisan ide-helper:generate
	docker compose exec app php artisan ide-helper:meta
	docker compose exec app php artisan ide-helper:models --nowrite
