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
- POST /api/logiN
- POST /api/logout
- GET /api/gifs/search?query=
- GET /api/gifs/{id}
- POST /api/favorite
- GET /api/favorites


## 🧪 TESTS
### Ejecutar Tests
Para ejecutar los tests, utilizamos el comando:
```
php artisan test
```
Sin embargo, es necesario asegurarse de que la base de datos de pruebas esté correctamente configurada antes de correr los tests.
- Pasos para preparar el entorno de pruebas:
### 1. Los test utilizan el archivo .env.testing
### 2. Crear la base de datos de pruebas que está en .env.testing
```
CREATE DATABASE challenge_test;
```
### 3. Ejecutar migraciones
### 4. Instalar Passport para entorno de testing
### 5. Correr las migraciones de passport y agregar las nuevas claves y secretos al .env.testing:

----------------------------------------------------------------------------------------------------
## DER
![image](https://github.com/user-attachments/assets/6feebe12-3a04-46f7-b736-23a2b3e72d0a)

## Diagrama de Casos de Uso:
![image](https://github.com/user-attachments/assets/6e513458-0023-4e02-809f-0f7a1e854f76)

## Diagrama de Secuencia:
LOGIN:
![image](https://github.com/user-attachments/assets/07f9684b-9db6-4f74-aafe-ccf418bf9f46)
LOGOUT:
![image](https://github.com/user-attachments/assets/34b9bd4a-1974-44a0-b9fa-629642185526)
GUARDAR GIF FAVORITO:
![image](https://github.com/user-attachments/assets/d587249b-d485-4ae3-88cd-786f9d64562a)
BUSCAR GIF POR UNA CADENA DE TEXTO
![image](https://github.com/user-attachments/assets/37375821-2ec7-4b70-a0ee-fce63509659f)
BUSCAR GIF POR ID DE GIPHY
![image](https://github.com/user-attachments/assets/6d650a29-8b0b-470e-9b94-0d6be516d925)
BUSCAR GIF FAVORITO POR ID
![image](https://github.com/user-attachments/assets/fc2a481f-f7f2-49a2-9923-d32ac535c082)
GUARDAR LOGS
![image](https://github.com/user-attachments/assets/b66133c2-3c3b-400a-a48e-d9cd7d8cef53)
VISUALIZAR LOGS
![image](https://github.com/user-attachments/assets/dd40c270-3dd8-4c2e-82ec-534c94a754e4)










