init:
	@echo ">>>>> Iniciando Docker"
	@docker-compose up -d

install:
	@echo ">>>>> Copiando Env example"
	@cp .env.example .env
	@echo ">>>>> Iniciando Docker"
	@docker-compose up -d db app
	@echo ">>>>> Composer install"
	@docker-compose exec app composer install
	@echo ">>>>> Generate key"
	@docker-compose exec app php artisan key:generate
	@echo ">>>>> Rodando Migrate e seed"
	@docker-compose exec app php artisan migrate
	@docker-compose exec app php artisan db:seed
	@echo ">>>>> Iniciando Worker"
	@docker-compose up -d worker
	@echo "Projeto Iniciando"
	@echo "Acesse http://localhost:8080"

test:
	@echo ">>>>> Rodando tests"
	@docker-compose exec app php artisan test

down:
	@echo ">>>>> Parando Docker"
	@docker-compose down
