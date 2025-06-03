# Sistema de Gerenciamento de Clientes - UNICAMPO

### Este projeto foi desenvolvido como parte da avaliação técnica para a vaga de Desenvolvedor Web na empresa UNICAMPO. O sistema permite o cadastro, listagem e gerenciamento de clientes com base em regras de negócio específicas, utilizando Laravel no backend e Vue.js no frontend.


## 🚀 Tecnologias Utilizadas

### Backend

- Laravel (PHP)

- MySQL

- Seeders & Migrations

- Validações customizadas.

- Swagger 

### Frontend

- Vue.js

- Vue Router / Vuex (se necessário)

- Axios para integração com API


### Outros (Diferenciais)

- Docker / Docker Compose 

- Testes Unitários 


## 📦 Instalação do Projeto (sem docker !)


### Pré-requisitos

- PHP 8.1+

- Composer

- Node.js 18+

- MySQL

- Docker [Opcional]


### Clonando o repositório

```
git clone https://github.com/georgesbrj/UniCampo

cd seu-repositorio
```

## 🛣 Ambiente Docker 

### Backend - Laravel

Caso esteja utilizando Docker:
```
1-renomei o arquivo .env.example para .env 

1-Rode o comando na pasta raiz do projeto => docker-compose up --build

2-Verifique os serviços com =>  docker ps -a 

3-Acesse o container do backend_app com => docker exec -it backend_app bash

4-Rode o comando => composer install

5-Rodar migrations e seeders => php artisan migrate --seed

6-Acesso a documentação do projeto http://localhost:8000/api/documentation#/

7-Caso não tenha gerado a chave laravel => php artisan key:generate

7-Rode o comando docker-compose down => Finaliza todos os containers 
```

## 🧪 Testes  
```
1-Verifique os serviços com =>  docker ps -a 

2-Acesse o container do backend_app com => docker exec -it backend_app bash

3-Rode o comando => php artisan test
```

### FrontEdn - Vue
```
1 - na pasta raiz do projeto rode o comando => docker ps -a verifique se o serviço frontend_app esta ativo 

2- acesse o container => docker exec -it frontend_app bash

3- rode o comando npm install
``` 

##  Ambiente wamp /xamp/mamp  (Backend) 
``` 
caso esteja usando wamp/mamp ou xamp depois de clonar o projeto va ate a pasta backend e na raiz da pasta 

1-Rode o comando ⇒  composer install

2-Rode o comando ⇒ php artisan key:generate

3-Criar e configurar o `.env` configure com os dados com seu banco local 

4-Rodar migrations e seeds => php artisan migrate --seed

5-Iniciar o servidor => php artisan serve
```

### Frontend - Vue.js

1. Acesse a pasta `frontend/`:
```
1. cd frontend
2. Instale as dependências: => npm install 
3. Inicie o projeto: => npm run dev
```
 
## 🧪 Testes  
```
1- Acessar a pasta backend 

2- Rodar o comando => php artisan test
```

## 🧩 Funcionalidades

- Cadastro de clientes em 3 etapas (wizard): Dados pessoais, endereço e profissão.

- Validação de:

  - CPF/CNPJ com máscara e dígito verificador.

  - E-mail com regex.

  - Telefone com máscara (com 8 ou 9 dígitos + DDD).

  - Campos obrigatórios conforme estrutura do banco.

- Filtro por status (ativo/inativo) e ordenação por data.

- Busca por nome.

- Evita duplicidade de CPF/CNPJ e e-mail.

- Exibição de mensagens de erro amigáveis e contextualizadas.

- Lookup de profissões fixas.

- Testes unitários 

## 📁 Documentação da API 

Swagger documentação estará disponível em:
```
http://localhost:8000/api/documentation
```

## 📌 Observações

- Projeto desenvolvido como parte do processo seletivo da UNICAMPO.

- Todos os requisitos funcionais e técnicos foram seguidos conforme o documento da avaliação.

## Screenshots
| Página Inicial | Cadastro |
|----------------|----------|
| ![home](https://github.com/georgesbrj/UniCampo/blob/main/backend/public/images/home.png) | ![cadastrar](https://github.com/georgesbrj/UniCampo/blob/main/backend/public/images/cadastar.png) |

| Edição | Documentação |
|--------|--------------|
| ![edit](https://github.com/georgesbrj/UniCampo/blob/main/backend/public/images/edit.png) | ![Documentacao](https://github.com/georgesbrj/UniCampo/blob/main/backend/public/images/documentation.png) |
