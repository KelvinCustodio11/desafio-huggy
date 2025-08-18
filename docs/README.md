Passo a passo abstrato para acompanhar desenvolvimento:

Criei as pastas com a base de Laravel e do Vue

Criei o docker composer e dockerfile

Ajustes de permissoes storage Laravel

Ajuste do .env

Criei o model, controller e factory: 
    - php artisan make:model Client -mcf
    - php artisan make:model Address -mcf

╰─❯ docker exec -it laravel_app php artisan migrate

Criei CRUD - Clients

Criei collection Postman

Extrair em service e repository

Inversao de dependencias

Criar as Form Requests
    php artisan make:request StoreClientRequest
    php artisan make:request UpdateClientRequest

Testes de integracao
Testes de Unidade
   ╰─❯ docker exec -it laravel_app php artisan test

Criar login com a huggy

Sync dados da huggy para o banco de dados

...

- 

- Criar sync contatos para a huggy

- Relatorios

- Agendar email para novos registros de contatos

- Call integracao VoIP

- Frontend

Desafio Huggy

DETALHAMENTO E INSTRUCOES PARA TESTAR A APLICACAO:
________________________________________________________________________________________________________________________________________________
    docker exec -it laravel_app php artisan route:list

      GET|HEAD        api/auth/huggy/callback ............ Auth\HuggyAuthController@callback
      GET|HEAD        api/auth/huggy/login ............... login › Auth\HuggyAuthController@redirect
      GET|HEAD        api/clients ........................ index › ClientController@index
      POST            api/clients ........................ store › ClientController@store
      GET|HEAD        api/clients/search ................. ClientController@searchByNameOrPhone
      GET|HEAD        api/clients/{client} ............... show › ClientController@show
      PUT|PATCH       api/clients/{client} ............... update › ClientController@update
      DELETE          api/clients/{client} ............... destroy › ClientController@destroy
      GET|HEAD        api/huggy/contacts/sync ............ HuggySyncController
      GET|HEAD        sanctum/csrf-cookie ................ sanctum.csrf-cookie › Laravel\Sanctum › CsrfCookieController@show
      GET|HEAD        storage/{path} ..................... storage.local       
________________________________________________________________________________________________________________________________________________

- ESTRUTURA PRINCIPAL:

project-root/
│
├── backend/        # Laravel API
│
├── frontend/       # Vue App
│
└── docker-compose.yml


Este projeto é baseado em Laravel e utiliza Docker para gerenciamento do ambiente de desenvolvimento.

Pré-requisitos

Docker e Docker Compose instalados.

Ngrok instalado (opcional, para expor localhost).

1. Iniciando o Docker

No terminal, dentro da pasta do projeto:

docker-compose up -d


Isso irá iniciar todos os containers definidos no docker-compose.yml, incluindo app, database e outros serviços necessários.

Para verificar os containers rodando:

docker-compose ps


Para acessar o container da aplicação:

docker-compose exec app bash


(Substitua app pelo nome do serviço da aplicação definido no seu docker-compose.yml)

2. Comandos Laravel essenciais

Dentro do container da aplicação (docker-compose exec app bash), rode:

# Instalar dependências do PHP
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Rodar migrations
php artisan migrate

# Rodar seeds (caso haja dados de exemplo)
php artisan db:seed (Sem uso por enquanto)

# Limpar cache da aplicação
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

3. Acessando a aplicação

Se você estiver usando Nginx + PHP-FPM, acesse via localhost na porta definida no Docker Compose, ex.:

http://localhost:8080


4. Expondo via Ngrok

Para permitir acesso externo (ex.: para testes de webhooks ou demos), use o Ngrok:

ngrok http 8000

Isso irá gerar um link público (https) que redireciona para sua aplicação local.



________________________
Estratégia passo a passo
________________________

- Dia 1 — Planejamento e Setup

Ler e entender o desafio (já fez isso agora 👍).

Criar repositório GitHub com estrutura frontend/ (Vue) e backend/ (Laravel).

Configurar ambiente local com Docker (Laravel + MySQL + Nginx, e servidor Node para Vue).

Criar projeto Laravel com Jetstream + API tokens (Sanctum) para autenticação.

Criar projeto Vue 3 com Vite e bibliotecas-base (Axios, Vue Router, TailwindCSS).

Definir modelos do banco (clients com nome, email, telefone, cidade, estado, foto, idade).

Escrever README inicial já com passos de instalação (mesmo que o código esteja vazio).

- Dia 2 — Backend API básica

Criar CRUD de clientes no Laravel (API Resource Controller).

Adicionar validações (FormRequest) para garantir dados corretos.

Criar migrations e seeders.

Implementar autenticação via Sanctum.

Criar rota /api/webhook que recebe dados externos e cria clientes no banco.

Configurar URL do webhook como variável de ambiente.

- Dia 3 — Webhook + Tarefas agendadas

Criar serviço que envia webhook ao endereço definido no .env.

Implementar Jobs com dispatch() para agendar envio de email de boas-vindas 30 minutos após cadastro.

Usar Mailables para envio do email.

Preparar testes básicos de integração (ex.: cadastro de cliente → email enviado).

- Dia 4 — Integração VOIP

Escolher provedor (ex.: Twilio).

Criar rota API para iniciar ligação se o contato tiver telefone.

No .env, colocar credenciais VOIP.

No frontend, criar botão de "Ligar" que chama essa rota.

- Dia 5 — Frontend CRUD + Busca

Criar páginas:

Lista de clientes (com busca por nome/email/telefone).

Formulário de novo cliente.

Editar cliente.

Excluir cliente (com modal de confirmação).

Mostrar botão de ligação VOIP apenas se tiver telefone.

Usar Axios para consumir API.

- Dia 6 — Relatórios + Funcionalidade Extra

Criar gráficos com ApexCharts:

Contatos por cidade.

Distribuição por faixa etária.

Funcionalidade extra sugerida (para pontos de criatividade):

Upload e preview de foto do cliente.

Tagging de clientes (ex.: VIP, Potencial, Inativo).

Melhorar UX: validação em tempo real, responsividade.

- Dia 7 — Testes, Documentação e Apresentação

Criar Collection Postman com todas as rotas (documentando parâmetros e exemplos).

Testes unitários e de integração básicos no Laravel (e2e não precisa ser complexo).

Revisar README com:

Instalação

Execução local

Variáveis de ambiente

Fluxo geral do sistema

Criar branch final e preparar apresentação de 20 minutos:

Começar com overview das techs.

Mostrar arquitetura.

Demonstrar funcionalidades.

Destacar funcionalidade extra.
