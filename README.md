# Laravel + Passport + Giphy API Challenge

Este proyecto es una API desarrollada en Laravel que permite:

- Autenticación de usuarios con Passport.
- Búsqueda de GIFs usando la API de Giphy.
- Guardado de GIFs favoritos por usuario.
- Registro de logs de cada solicitud realizada.

---

## 🚀 Requisitos

- Docker y Docker Compose
- Acceso a consola WSL (opcional, pero recomendado en Windows)
- Laravel 10
- PHP 8.2

---

##### Pasos para levantar el entorno

### 1. Clonar el repositorio

### 2. Levantar contenedores
Desde la raíz del proyecto, ejecutar:
```
docker-compose up -d --build
```

### 3. Instalar dependencias con Composer
Ingresar a la consola del contenedor web:

```
docker exec -it nombre_del_contenedor_web bash
composer install
```

### 4. Otorgar permisos (solo si usás WSL)
Desde tu terminal Linux:
```
cd ruta/al/repositorio
sudo chown [tu_usuario]:www-data storage -R
sudo chmod 775 storage -R
sudo chown www-data:www-data bootstrap/cache -R
```

### 5. Configurar variables de entorno
Copiar el archivo .env.example y renombrarlo a .env:
```
cp .env.example .env
```
Asegurate de que estas variables estén presentes en tu .env:
```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=challenge
DB_USERNAME=root
DB_PASSWORD=

GIPHY_API_KEY=NfXXXXXXXX
```

### 6. Generar clave de aplicación
Dentro del contenedor web:
```
php artisan key:generate
```

### 7. Ejecutar migraciones
```
php artisan migrate
```
### 8. Instalar Passport
```
php artisan passport:install
```
Luego de ejecutar el comando, copiar el Client ID y Client Secret mostrados por consola y agregarlos en el archivo .env:
```
PASSPORT_CLIENT_ID=8
PASSPORT_CLIENT_SECRET=9qvamzgk56Op33e89dqbX2AVPIrE1WbEkqNxqCoD
```

Endpoints principales
    POST /api/logiN
    POST /api/logout
    GET /api/gifs/search?query=
    GET /api/gifs/{id}
    POST /api/favorite
    GET /api/favorites
















