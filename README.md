# Clean Architecture - PHP

## Sobre o projeto

Clean Architecture - PHP é uma aplicação backend criada para estar desenvolvendo os conhecimentos adquiridos sobre DDD e Clean Architecture.

## Como executar o projeto

```bash
    # Clonar o repositório com uma das opções abaixo
    git clone https://github.com/williamtrevisan/clean-architecture-php.git
    git clone git@github.com:williamtrevisan/clean-architecture-php.git
    
    # Entrar na pasta do projeto
    cd clean-architecture-php
    
    # Subir o container do projeto
    docker-compose up -d
    
    # Acessar o container app
    docker-compose exec app bash
    
    # Instalar as dependências do projeto
    composer install
    
    # Para executar os testes
    ./vendor/bin/phpunit
```

## Tecnologias utilizadas

- PHP
- Eloquent ORM
- Pest
- Mockery
- Docker

## Autor

William Trevisan

https://www.linkedin.com/in/william-trevisan/
