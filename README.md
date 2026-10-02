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

# Simulador de recuperação tributária

Módulo que estima, a partir de poucas informações da empresa, quanto ela pode recuperar em tributos
pagos indevidamente nos últimos 5 anos (PIS/COFINS, INSS, ICMS, ISS, IRPJ/CSLL), e registra o contato como lead.

- Página pública (sem o layout administrativo): `http://localhost:8080/simulador`
- Simulações recebidas (menu **Simulador tributário › Simulações**): `http://localhost:8080/simulacao`
- Rode `make install` (ou `php artisan migrate`) para criar a tabela `simulacoes`.

### Como funciona
1. O usuário informa regime tributário, segmento, faturamento e período (até 60 meses).
2. O formulário pede apenas os valores usados pelas teses aplicáveis ao perfil (folha, energia, combustíveis, ICMS, ISS ou % de produtos monofásicos).
3. Para cada tese aplicável: `valor mensal = base × percentual` (faixa mínima e máxima), `principal = valor mensal × meses` e correção pela SELIC simples acumulada.
4. O resultado fica salvo e é exibido em `/simulador/resultado/{uuid}`.

### Configuração
As teses, percentuais e a SELIC ficam em `config/simulador.php`; ajuste-os com a equipe tributária.
Para incluir uma nova tese basta adicionar uma entrada no array `teses`, sem alterar código.

| Variável | Descrição |
|---|---|
| `SIMULADOR_EMPRESA` | Nome exibido no topo da página pública |
| `SIMULADOR_WHATSAPP` | Número com DDI e DDD (ex.: `5585999999999`) para o botão "Falar com um especialista" |
| `SIMULADOR_SELIC_MENSAL` | SELIC média mensal usada na correção (`0` desativa) |

A regra de cálculo fica em `biblioteca/Simulador` (domínio, casos de uso e testes), na mesma estrutura do módulo de livros.
