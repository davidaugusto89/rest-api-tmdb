# REST API TMDb

Back-end desenvolvido utilizando Laravel 8, para requisição das informações dos filmes do site TMDb.

## Requisitos básicos

PHP >= 7.3

Composer

Git


## Guia de Instalação

Para instalar a aplicação deve ser executados os seguintes comandos no terminal.

```sh
git clone -b develop https://github.com/davidaugusto89/rest-api-tmdb.git rest-api-tmdb
cd rest-api-tmdb
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```