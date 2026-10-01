# Token API

API REST sencilla para gestionar usuarios. Permite registrar cuentas, iniciar sesión, consultar usuarios y cambiar un nombre de usuario mediante un token.

> **Estado:** proyecto en desarrollo. La autenticación y los endpoints disponibles son básicos; el controlador de tokens independiente todavía no está implementado.

## Tecnologías

- PHP 8.3 o superior
- Laravel 13
- SQLite (configuración inicial)
- Vite y Tailwind CSS 4 para los recursos frontend

## Requisitos

Necesitas PHP 8.3+, Composer y Node.js con npm.

## Instalación

Desde la carpeta del proyecto, ejecuta:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

En Windows PowerShell, usa `Copy-Item .env.example .env` en lugar de `cp` si fuera necesario.

La configuración de ejemplo usa SQLite. Crea el archivo de base de datos si todavía no existe y ejecuta las migraciones:

```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
```

Instala las dependencias frontend y compila los recursos:

```bash
npm install
npm run build
```

Para iniciar el servidor de desarrollo:

```bash
php artisan serve
```

La API estará disponible en `http://localhost:8000/api`.

## Endpoints

| Método | Ruta | Descripción |
|---|---|---|
| `GET` | `/api/user/get` | Devuelve usuarios paginados (10 por página). |
| `POST` | `/api/user/create` | Registra un usuario. |
| `POST` | `/api/user/login` | Valida credenciales y emite un token. |
| `POST` | `/api/user/update_username` | Cambia el nombre de usuario usando un token. |

### Registrar usuario

```bash
curl -X POST http://localhost:8000/api/user/create \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username":"ana","email":"ana@example.com","password":"una-clave-segura"}'
```

### Iniciar sesión

```bash
curl -X POST http://localhost:8000/api/user/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"ana@example.com","password":"una-clave-segura"}'
```

La respuesta incluye el token generado. Envíalo en el cuerpo JSON al cambiar el nombre:

```bash
curl -X POST http://localhost:8000/api/user/update_username \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"username":"ana-nueva","token":"TOKEN_DEVUELTO_AL_INICIAR_SESION"}'
```

Las contraseñas se guardan con hash y no se incluyen en las respuestas del modelo `User`. El inicio de sesión reemplaza el token anterior del usuario.
