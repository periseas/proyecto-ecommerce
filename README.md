# **Proyecto e-commerce sencillo**

## **Descripción**

Este proyecto es una aplicación web construida con **Laravel**, **Docker**, **MongoDB** como base de datos y  **Vuetify**, **Vue** para el frontend. El proyecto está configurado para trabajar con contenedores Docker.

## **Tecnologías Usadas**

- **Laravel**: Framework PHP
- **MongoDB**: Base de datos NoSQL
- **Docker**: Contenedores para la aplicación
- **Vuetify**: Framework de componentes para Vue.js
- **Vue.js**: Framework para frontend

## **Requisitos del Sistema**

- Docker
- Docker Compose
- PHP 8.1 o superior
- Composer
- Node.js 

## **Instalación y Configuración**

### 1. **Clonar el Repositorio**

Clona este repositorio en tu máquina local:

git clone https://github.com/periseas/proyecto-ecommerce.git
cd repositorio

### 2. **Configurar Docker** 
Verifica de tener Docker y Docker Compose instalados 
```
docker-compose up -d --build
```
### 3. **Instalar Laravel**
```
docker exec -it laravel-app bash
composer install
php artisan key:generate
```
### 4. **Ejecuta las migraciones**
```
php artisan migrate
```
### 5. **Ejecuta el seeder**
```
php artisan db:seed --class=FakeStoreSeeder
```

### 6. **Configuración el archivo .env**
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mongodb
DB_HOST=mongo
DB_PORT=27017
DB_DATABASE=proyecto
DB_USERNAME=
DB_PASSWORD=
```

### 7. **Configurar el Frontend para usar Vue.js y Vuetify**

```
docker exec -it laravel-app bash
npm install
npm run dev
```

![Demo en proyecto](https://media4.giphy.com/media/v1.Y2lkPTc5MGI3NjExMmpxNWE4djU4Zmx1ZGtjOWR3Zms3aTJzZDNmaGthNnlrbTQ2bWlqZyZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/UzYshvMKae7B9nUDbv/giphy.gif)

![Demo en proyecto](https://media0.giphy.com/media/v1.Y2lkPTc5MGI3NjExMjdvOHFqbWxlZWdyMjh3anNnaTVpMzR2bXkyams0emljNm81MTBveSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/1xykf4XM6HnOMngmeB/giphy.gif)
