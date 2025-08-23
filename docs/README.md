Desafio Huggy

---

### 1. Pré-requisitos

- Docker e Docker Compose instalados
- Node.js e npm instalados (para rodar o frontend localmente)
- Ngrok instalado (opcional, para expor localhost)
- Conta na Huggy (https://app.huggy.io/) e uma aplicação criada para obter as credenciais de integração (Client ID, Client Secret, Redirect URI)

---

### 2. Clonando o Projeto e Estrutura

Clone o repositório para sua máquina local:
```sh
git clone https://github.com/KelvinCustodio11/desafio-huggy.git
cd desafio-huggy
```

Estrutura esperada:
```
project-root/
│
├── backend/        # Laravel API
├── frontend/       # Vue App
├── nginx/          # Proxy
├── docs/           # Collection Postman
└── docker-compose.yml
```
### 3. Configuração Inicial

#### Backend (Laravel)

1. Suba os containers:
  ```sh
  docker-compose up -d
  ```
2. Acesse o container da aplicação:
  ```sh
  docker-compose exec app bash
  ```
3. Instale as dependências PHP:
  ```sh
  composer install
  ```
4. Copie o arquivo de ambiente e edite as credenciais:
  ```sh
  cp .env.example .env
  # Edite o .env conforme abaixo
  ```
5. Gere a chave da aplicação:
  ```sh
  php artisan key:generate
  ```
6. Configure as credenciais Huggy no `.env`:
  ```env
  HUGGY_CLIENT_ID=seu_client_id
  HUGGY_CLIENT_SECRET=seu_client_secret
  HUGGY_REDIRECT_URI=https://SEU_NGROK/api/auth/huggy/callback
  ```
  > ⚠️ Crie uma conta e uma aplicação em https://app.huggy.io/ para obter essas credenciais.
7. Rode as migrations:
  ```sh
  php artisan migrate
  ```
8. (Opcional) Rode os seeders:
  ```sh
  php artisan db:seed
  ```

9. Limpe os caches:
  ```sh
  php artisan config:clear
  php artisan cache:clear
  php artisan route:clear
  php artisan view:clear
  ```

10. Rode o worker de filas (para processar jobs agendados, como envio de e-mail):
  ```sh
  php artisan queue:work
  ```

#### Frontend (Vue)

1. Instale as dependências:
  ```sh
  cd frontend
  npm install
  ```
2. Rode o servidor de desenvolvimento:
  ```sh
  npm run dev
  ```

---

### 4. Acessando a Aplicação

- Frontend: http://localhost:5173/
- API Backend: http://localhost:8080/
- Banco de Dados (phpMyAdmin): http://localhost:8081/
  - Usuário: huggy_user
  - Senha: huggy_pass

---

### 5. Expondo via Ngrok (para webhooks ou testes externos)

```sh
ngrok http 8080
```
Use o link gerado para configurar o `HUGGY_REDIRECT_URI` no .env e na aplicação Huggy.

---

### 6. Testes

#### Backend
```sh
docker exec -it laravel_app php artisan test
```

#### Frontend
```sh
cd frontend
npm run test (Por enquanto nao temos)
```

---

### 7. Rotas Principais da API

```

DETALHAMENTO DE ROTAS:
__________________________________________________________________________________________
    docker exec -it laravel_app php artisan route:list

  GET|HEAD        api/auth/huggy/callback ........... Auth\HuggyAuthController@callback
  POST            api/auth/huggy/exchange ....... Auth\HuggyAuthController@exchangeCode
  GET|HEAD        api/auth/huggy/login ...... login › Auth\HuggyAuthController@redirect
  GET|HEAD        api/clients .......................... index › ClientController@index
  POST            api/clients .......................... store › ClientController@store
  GET|HEAD        api/clients/search ............. ClientController@searchByNameOrPhone
  GET|HEAD        api/clients/{client} ................... show › ClientController@show
  PUT|PATCH       api/clients/{client} ............... update › ClientController@update
  DELETE          api/clients/{client} ............. destroy › ClientController@destroy
  POST            api/clients/{client}/call ....................... VoipController@call
  GET|HEAD        api/huggy/contacts/sync ......................... HuggySyncController
  POST            api/huggy/contacts/webhook .... HuggySyncController@syncClientWebhook
  GET|HEAD        api/reports ................................ ReportController@reports      
__________________________________________________________________________________________

```
---

### 8. Observações

- Certifique-se de que as variáveis de ambiente estejam corretas e que a chave do Laravel (`APP_KEY`) tenha sido gerada.
- Para integração com a Huggy, é obrigatório criar uma aplicação na plataforma Huggy e configurar as credenciais no `.env`.
- Para webhooks, utilize o Ngrok para expor seu backend local.
- Use a collection Postman disponível em `/docs` para testar as rotas da API, atente-se para alterar o Bearer Token da Autenticacao.
```