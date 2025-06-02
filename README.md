# <img src="https://github.com/user-attachments/assets/46aac08b-80d9-40e3-b226-f912ec875555" style="height:30px;width:30px"/> Paggue - Sistema de Gestão de Eventos e Pagamentos
Este é o **desafio técnico Paggue**, um sistema completo para gerenciamento de eventos e processamento de pagamentos online.

## 📋 Tecnologias Utilizadas

* **PHP** >= 8.x
* **Laravel** 10.x
* **Eloquent ORM**
* **PostgreSQL**
* **Docker & Docker Compose**
* **Redis** (Filas)
* **Laravel Passport** (Autenticação API)
* **Spatie Laravel Permission** (Controle de Acesso)
* **Pest PHP** (Testes)

## ⚙️ Funcionalidades Principais

* Cadastro e autenticação de usuários (Admin, Produtor, Cliente)
* Gerenciamento de roles e permissions via Spatie
* CRUD completo de:

  * Produtores
  * Eventos (com upload de banner)
  * Setores
  * Lotes
  * Ingressos
  * Cupons de desconto
* Upload de banners para AWS S3 em ambiente de produção
* Integração com gateway de pagamentos (API Pix/Pagamentos)
* Processamento de pagamentos em background usando Redis Queue
* Webhook para confirmação de pagamento e disparo de eventos
* Envio de notificações (email/SMS) após processamento de pagamento

## 📦 Pré-requisitos
* PHP
* Docker e Docker Compose
* Git
* Composer

## 🚀 Instalação e Configuração

1. Clone este repositório e acesse a pasta do projeto:

   ```bash
   git clone <URL_DO_REPOSITORIO>
   cd paggue
   ```
2. Copie o arquivo de ambiente e ajuste variáveis no arquivo `.env`:

   ```bash
   cp .env.example .env
   ```

   * `DATABASE_URL=postgres://usuario:senha@db:5432/paggue`
   * `REDIS_HOST=redis`
   * `AWS_S3_BUCKET=<seu-bucket-S3>`
   * `PASSPORT_CLIENT_ID` e `PASSPORT_CLIENT_SECRET`
   * `PAYMENT_GATEWAY_URL`, `PAYMENT_GATEWAY_KEY`, etc.
3. Suba os containers Docker:

   ```bash
   docker-compose up -d
   ```
4. Instale dependências e prepare a aplicação:

   ```bash
   docker-compose exec app composer install
   docker-compose exec app php artisan key:generate
   docker-compose exec app php artisan migrate --seed
   docker-compose exec app php artisan passport:install
   ```

## 🚩 Uso

* Acesse a aplicação em: `http://localhost:8080`
* Base da API: `http://localhost:8080/api/v1`
* Liste as rotas da API:

  ```bash
  docker-compose exec app php artisan route:list --path=api/v1
  ```

## 🔄 Filas e Jobs

* **Redis** configurado como driver de filas no `config/queue.php`.
* Jobs principais:

  1. `ProcessPaymentJob` (fila `payments`): envia requisição ao gateway de pagamento.
  2. `HandlePaymentWebhookJob` (fila `default`): processa retorno do webhook.
  3. `SendNotificationJob` (fila `notifications`): notifica usuário via email/SMS.
* Inicie o worker de filas:

  ```bash
  docker-compose exec app php artisan queue:work --queue=payments,default,notifications
  ```

## 🧪 Testes

* Execute a suíte de testes com Pest:

  ```bash
  docker-compose exec app vendor/bin/pest
  ```

## 🔐 Controle de Acesso e Autenticação

* **Spatie Laravel Permission**:

  * Roles: `admin`, `producer`, `client`
  * Permissions configuradas em `database/seeders/PermissionSeeder`
* **Laravel Passport** para autenticação via tokens OAuth2.

## 🤝 Contribuições

1. Faça um fork do projeto
2. Crie uma branch de feature: `git checkout -b feature/nome-da-feature`
3. Faça commits atômicos: `git commit -m "Descreva sua mudança"`
4. Envie para o branch remoto: `git push origin feature/nome-da-feature`
5. Abra um Pull Request 😊

## 📄 License

Este projeto está sob a licença **MIT**.

## 🚀 Pontos críticos a serem resolvidos (fiquei doente no meio do processo seletivo)
* Integração com o pix web da Paggue
* Deploy em ambiente Kubernetes (k3s)
* Aumentar a coverage de tests para 80% (está em 11,7%)
