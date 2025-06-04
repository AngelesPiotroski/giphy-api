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
1. El usuario envía su email y password.
2. Se validan los datos.
3. Si son inválidos se responde con error 422.
4. Si son válidos se busca el usuario en la base.
5. Si el usuario no existe o la contraseña es incorrecta se responde con error 401.
6. Si todo está correcto se genera un token que dura 30 minutos.
7. Se devuelve el token, la expiración y los datos del usuario.
8. Si ocurre un error inesperado se registra y se responde con error 500.

![image](https://github.com/user-attachments/assets/07f9684b-9db6-4f74-aafe-ccf418bf9f46)

LOGOUT:
1. El usuario pide cerrar sesión.
2. Se obtiene el usuario autenticado.
3. Si no hay sesión se responde con error 401.
4. Si hay sesión se revoca el token.
5. Se devuelve mensaje de logout exitoso.
6. Si ocurre un error se registra y se responde con error 500.

![image](https://github.com/user-attachments/assets/34b9bd4a-1974-44a0-b9fa-629642185526)

GUARDAR GIF FAVORITO:
1. Usuario envía datos (gif_id, alias, user_id) por POST.
2. Controlador valida los datos.
3. Si fallan, se responde con error 422.
4. Si son válidos, llama al servicio para guardar.
5. El servicio crea el favorito en base de datos.
6. Si todo OK, se responde con 201 y los datos.
7. Si hay error, se registra con el logger y se responde con error 500.

![image](https://github.com/user-attachments/assets/d587249b-d485-4ae3-88cd-786f9d64562a)

BUSCAR GIF POR UNA CADENA DE TEXTO
1. El usuario envía una búsqueda con texto y filtros opcionales.
2. El sistema valida los datos.
3. Si hay errores, se responde con un error 422.
4. Si todo está correcto, consulta la API de Giphy.
5. Si Giphy falla, se registra el error y se responde con un error 500.
6. Si funciona, se devuelve la lista de GIFs (200 OK).

![image](https://github.com/user-attachments/assets/37375821-2ec7-4b70-a0ee-fce63509659f)

BUSCAR GIF POR ID DE GIPHY
1. El usuario solicita un GIF por su ID.
2. Se valida que el ID no esté vacío.
3. Si está mal, se responde con un error 422.
4. Si está bien, se busca el GIF en Giphy.
5. Si hay error, se registra y se responde con un error 500.
6. Si está todo correcto, se devuelve el GIF (200 OK).

![image](https://github.com/user-attachments/assets/6d650a29-8b0b-470e-9b94-0d6be516d925)

BUSCAR GIF FAVORITO POR ID

1. El usuario pide un GIF favorito por su ID.
2. Se valida el ID y se verifica que exista en la base.
3. Si no pasa, se responde con un 422.
4. Se obtiene el usuario logueado y se busca el favorito.
5. Si no se encuentra, se responde con un 404.
6. Si se encuentra, se busca el GIF en Giphy.
7. Si Giphy falla, se registra el error y se responde con un error 500.
8. Si todo está bien, se devuelve el GIF (200 OK).

![image](https://github.com/user-attachments/assets/fc2a481f-f7f2-49a2-9923-d32ac535c082)

GUARDAR LOGS

1. El middleware llama al servicio para guardar un log.
2. Se obtiene el ID del usuario autenticado.
3. Se guarda el log en la base de datos (service_logs).
4. La acción continúa normalmente y el usuario recibe la respuesta de su solicitud.

![image](https://github.com/user-attachments/assets/b66133c2-3c3b-400a-a48e-d9cd7d8cef53)

VISUALIZAR LOGS

1. El usuario pide ver los logs.
2. El controlador busca los logs en la base de datos.
3. Devuelve los resultados en formato JSON.
4. Si hay error, lo registra con ErrorLogger y responde con mensaje de error 500.
 
![image](https://github.com/user-attachments/assets/dd40c270-3dd8-4c2e-82ec-534c94a754e4)











