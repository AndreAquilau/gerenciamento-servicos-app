# Projeto de Gerenciamento de Comissões e Serviços 

# Initial Project With Laravel
>Installation
```bash
composer create-project laravel/laravel gerenciamento-servicos
```

# Installation Laravel Jetstream For Authentication
```bash
composer require laravel/jetstream
```
```bash
php artisan jetstream:install livewire
```
```bash
php artisan jetstream:install livewire --teams
```
# Finalizing The Installation
```bash
npm install
npm run dev
php artisan migrate

php artisan vendor:publish --tag=jetstream-views

npm run dev
```

#### References 
[Laravel Jetstream](https://jetstream.laravel.com/2.x/installation.html) <br/>
[Laravel](https://laravel.com/docs/8.x#why-laravel)