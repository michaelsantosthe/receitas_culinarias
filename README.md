# 🍳 Backend API – Receitas Culinárias  

Este é o **projeto Backend em Laravel** para gerenciamento de usuários, categorias e receitas culinárias.  
Ele utiliza **Docker** para ambiente de desenvolvimento, **MySQL** como banco de dados e possui documentação de API gerada via **Swagger**.  

---

## 🚀 Pré-requisitos

Antes de iniciar, certifique-se de ter instalado em sua máquina:  
- [Docker](https://docs.docker.com/get-docker/)  
- [Docker Compose](https://docs.docker.com/compose/)  

---

## 📦 Subindo o ambiente

### 1. Build inicial do container

docker-compose up -d --build

### 2. Subir os containers (após o build inicial)

docker-compose up -d

⚙️ Configuração do projeto Laravel

### 3. Instalar dependências PHP

docker-compose exec app composer install

### 4. Gerar a chave da aplicação

docker-compose exec app php artisan key:generate

### 5. Rodar as migrations do banco de dados

docker-compose exec app php artisan migrate

🔧 Acessando o container

docker exec -it app bash

Dentro dele, habilite o módulo rewrite do Apache:

a2enmod rewrite

### 📖 Documentação da API (Swagger)

A documentação da API é gerada automaticamente via L5-Swagger
.
Para gerar/atualizar a doc: 

docker-compose exec app php artisan l5-swagger:generate

Após isso, acesse a documentação no navegador:

👉 http://localhost:8080/api/documentation

### 🛠 Tecnologias utilizadas

- PHP 8.4 + Laravel 12

- Docker & Docker Compose

- MySQL 8

- Swagger (L5-Swagger) para documentação de API
