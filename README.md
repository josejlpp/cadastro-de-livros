# Como rodar o sistema

### Requisitos
- Docker
- Docker Compose
- Make (opcional)
- obs: é possivel que haja problemas com a conexão com banco de dados no windows, nesse caso é necessario alterar o arquivo .env e trocar o valor de `DB_HOST=db` para `DB_HOST={endereço correto}
### Passos

- Clone o repositório
- entre na pasta do projeto
- Execute o comando `make install`
- se não tiver o make instalado ou não quiser instalar, segue os comandos:
    - `cp .env.example .env`
    - `docker-compose up -d db app`
    - `docker-compose exec app composer install`
    - `docker-compose exec app php artisan key:generate`
    - `docker-compose exec app php artisan migrate`
    - `docker-compose exec app php artisan db:seed`
    - `docker-compose up -d worker`
    - O sistema já está pronto para uso
- acesso o sistema em `http://localhost:8080`
- para desligar o sistema execute o comando `make down`

### Inicio do sistema após instalado
- Execute o comando `make`

# Como rodar os testes
- Execute o comando `make test`
