# Hibrido | Teste Backend

## Índice
- [Como rodar a aplicação](#rodaraplicacao)
- [Arquiteturas](#arquiteturas)
- [Bibliotecas de terceiros](#bibliotecasdeterceiros)

## <a href="rodaraplicacao"></a>Como rodar a aplicação:

Para rodar a aplicação, será necessário:

### 1 Configurar a conexão com o banco de dados MySQL
No arquivo **src/Support/config.php** existem as constantes predefinidas. Será necessário apenas modificar os valores de **host** e **dbname** na constante "**CGF_DB_MYSQL**" e  os valores de "**CFG_DB_USER**" e "**CFG_DB_PASSWORD**". 

```php
//aquivo src/Support/config.php

### DATABASE ###
define('CFG_DB_MYSQL', "mysql:host=localhost;dbname=hibridobackend");
define('CFG_DB_USER', 'root');
define('CFG_DB_PASSWORD', '1234');
```

### Criar a estrutura da tabela "clients"

Para criar a estrutura da tabela de clientes, pode ser utilizado o script abaixo:
```sql
USE hibridobackend;

CREATE TABLE IF NOT EXISTS clients (
	cpf VARCHAR(11) PRIMARY KEY NOT NULL,
	name VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL UNIQUE,
	phone VARCHAR(15),
	created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP 
);
```

Um dump pode ser feito na tabela para agilizar a realização dos testes:
```sql
USE hibridobackend;

INSERT 
    INTO clients (cpf, name, email, phone) 
    VALUES 
        (12345678910, 'Lionel Messi', 'messi@mail.com', '11999999999'),
        (29876543210, 'Neymar Junior', 'neymar@mail.com', '21999998888'),
        (98765432345, 'Cristiano Ronaldo', 'ronaldo@mail.com', '81999997777'),
        (76543210987, 'Ibrahimovic', 'ibrahimovic@mail.com', '27999996666'),
        (65432109876, 'Robert Lewandowski', 'lewandowski@mail.com', '31999995555');
```

### 2 Subir um servidor HTTP:

Recomendo utilizar o próprio servidor embutido do PHP, rodando o seguinte comando:
```bash
php -S localhost:8080 
```

### 3 Consumir as rotas:

Boas opções de plataformas para consumo de API REST: 
- Insomnia: https://insomnia.rest/download/ 
- Postman: https://www.postman.com/downloads/

**1)** Fazer uma requisição com o **método GET** para a URL: ```/clientes```, retorna todos os clientes armazenados no banco em formato **JSON**;

**2)** Fazer uma requisição com o **método GET** para a URL: ```/clientes/{cpf}```, enviando na URL o **cpf** do cliente que se deseja buscar (ex: "/clientes/12345678910");

**3)** Fazer uma requisição com o **método POST** para a URL: ```/clientes```, enviando um **JSON** no corpo da requisição com os dados necessários para cadastro.

Exemplo:
```json
{
	"cpf": "65432789012",
	"name": "Ronaldinho Gaúcho",
	"email": "ronaldinho@mail.com",
	"phone": "27999994444"
}
```
Retorna a resposta se o cadastro foi bem sucedido ou não, em formato **JSON**.

**4)** Fazer uma requisição com o **método PUT** para a URL: ```/clientes/{cpf}```, enviando:
- O **cpf** do cliente que se deseja alterar na URL (ex: "/clientes/12345678910");
- Um **JSON** no corpo da requisição com os dados necessários para alteração;

Exemplo:
```json
{
	"name": "Zinedine Zidane",
	"email": "zidane@mail.com",
	"phone": "27999994444"
}
```
Retorna a resposta se a alteração foi bem sucedida ou não, em formato **JSON**;

**5)** Fazer uma requisição com o **método DELETE** para a URL: ```/clientes/{cpf}```, enviando na URL o **cpf** do cliente que se deseja remover (ex: "/clientes/12345678910");


## <a name="arquiteturas"></a>Arquiteturas

### MVC
A construção da arquitetura da aplicação se baseou no conceito Model-View-Controller (MVC), porém, sem a camada "View", já que se trata de uma API.

A estrutura de pastas e arquivos é a seguinte:
```
src
    Controllers
        CustomerController.php
    Database
        database.sqlite
    Models
        Model.php
        Customer.php
    Services
        Connection.php
        Log.php
    Storage
        Logs
            log.txt      
    Support
        config.php
        helpers.php
vendor
index.php
```

- **index.php:** Ponto de entrada de aplicação e que implementa as rotas conforme a URL requisitada;
- **Controllers/CustomerController.php:** Classe que recebe as requisições, encaminha para tratamento e devolve a resposta ao cliente;
- **Models/Model.php:** Classe responsável por realizar a comunicação com o banco de dados (Layer Supertype Pattern);
- **Models/Customer.php:** Classe responsável por implementar as regras de negócio associadas a entidade cliente (customer);
- **Services/Connection.php:** Classe que abstrai a conexão com o banco de dados (Static Creation Method Pattern);
- **Services/Log.php:** Classe que abstrai as funcionalidades do componente "monolog" para o controle de logs da aplicação conforme recomendações da "PSR 3 Logger Interface";
- **Storage/Logs/log.txt:** Arquivo que recebe os logs da aplicação;
- **Support/config.php:** Arquivo que centraliza constantes da aplicação;
- **Support/helpers.php:** Arquivo que implementa funções de apoio (helpers) para otimização das classes e métodos.
- **vendor:** Diretório que armazena as dependências da aplicação.

## <a name="bibliotecasdeterceiros"></a>Biblioteca de Terceiros

### bramus/router


### monolog/monolog