# Prueba Técnica PHP — Blog SPA con API REST

Proyecto desarrollado en PHP nativo con arquitectura MVC para gestión de publicaciones y autenticación de usuarios.

## Estructura del Proyecto

```
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      # Login y logout
│   │   ├── PostController.php      # Blog público y creación de posts
│   │   └── AdminController.php     # Panel de administración
│   └── Models/
│       ├── Post.php                # Modelo de publicaciones
│       └── User.php                # Modelo de usuarios
├── config/
│   ├── database.php                # Configuración de base de datos (no subir al repo)
│   └── database.example.php        # Ejemplo de configuración
├── views/
│   ├── admin/
│   │   ├── index.php               # Listado de posts
│   │   ├── login.php               # Formulario de ingreso
│   │   └── show.php                # Detalle de un post
│   ├── blog/
│   │   └── index.php               # Blog público con formulario AJAX
│   └── layouts/
│       ├── header.php              # Header compartido
│       └── footer.php              # Footer compartido
├── public/
│   ├── css/
│   │   └── style.css               # Estilos globales
│   ├── uploads/                    # Imágenes subidas por usuarios
│   └── index.php                   # Router principal
├── api/
│   ├── auth.php                    # Endpoint público de autenticación
│   ├── posts.php                   # Endpoints protegidos de posts
│   └── helpers.php                 # Funciones auxiliares (Basic Auth, JSON)
└── database/
    ├── schema.sql                          # Tablas users y posts
    ├── query1_productos_mas_vendidos.sql   # Consulta SQL 1
    └── query2_ventas_por_cliente.sql       # Consulta SQL 2
```

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- XAMPP (Apache + MySQL)

## Instalación

1. **Clonar el repositorio dentro de la carpeta htdocs de XAMPP**
   ```bash
   git clone <repository-url> "Prueba tecnica"
   ```

2. **Configurar la base de datos**
   - Abrir phpMyAdmin en `http://localhost/phpmyadmin`
   - Crear una base de datos nueva (ej: `prueba tecnica`)
   - Importar `database/schema.sql`
   - Importar `database/facturacion-detalle.sql` para las consultas SQL

3. **Configurar las credenciales**
   - Copiar `config/database.example.php` como `config/database.php`
   - Ajustar `DB_NAME` y `BASE_URL` según tu entorno:
   ```php
   define('DB_NAME', 'prueba tecnica');
   define('BASE_URL', '/Prueba tecnica/public');
   ```

4. **Acceder a la aplicación**
   ```
   http://localhost/Prueba tecnica/public/index.php?page=blog
   ```

## Credenciales de prueba

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Administrador | admin | 123456 |

## Uso

### Blog público
Cualquier persona puede acceder sin login:
```
http://localhost/Prueba tecnica/public/index.php?page=blog
```
- Ver todas las publicaciones ordenadas de más reciente a más antigua
- Crear una publicación con título, contenido, imagen y correo
- Validación de campos en frontend y backend
- Captcha reCAPTCHA para evitar bots
- El formulario no recarga la página (AJAX)

### Panel de administración
Solo accesible con sesión iniciada:
```
http://localhost/Prueba tecnica/public/index.php?page=login
```
- Listar todas las publicaciones
- Ver detalle completo de una publicación
- Eliminar publicaciones
- Cerrar sesión

---

## API REST

La API usa **Basic Auth** en todos los endpoints excepto `/api/auth.php`.

### Cómo configurar Basic Auth en Postman
1. Abre la pestaña **Authorization**
2. Selecciona **Basic Auth**
3. Ingresa `admin` / `123456`

---

### 🔓 POST `/api/auth.php` — Autenticación (público)

Verifica las credenciales del usuario. No requiere Authorization header.

**Request**
```
POST http://localhost/Prueba tecnica/api/auth.php
Content-Type: application/json
```
```json
{
    "username": "admin",
    "password": "123456"
}
```

**Respuesta exitosa — 200**
```json
{
    "success": true,
    "message": "Autenticación correcta.",
    "user": "admin"
}
```

**Respuesta fallida — 401**
```json
{
    "error": "Credenciales incorrectas."
}
```

---

### 🔒 GET `/api/posts.php` — Listar posts

Retorna todas las publicaciones ordenadas de más reciente a más antigua.

**Request**
```
GET http://localhost/Prueba tecnica/api/posts.php
Authorization: Basic YWRtaW46QWRtaW4xMjM0
```

**Respuesta exitosa — 200**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Mi primera publicación",
            "content": "Contenido del post",
            "image": "uploads/img_abc123.jpg",
            "email": "usuario@ejemplo.com",
            "created_at": "2026-04-23 11:07:00"
        }
    ]
}
```

---

### 🔒 GET `/api/posts.php?id=1` — Detalle de un post

**Request**
```
GET http://localhost/Prueba tecnica/api/posts.php?id=1
Authorization: Basic YWRtaW46QWRtaW4xMjM0
```

**Respuesta exitosa — 200**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Mi primera publicación",
        "content": "Contenido del post",
        "image": "uploads/img_abc123.jpg",
        "email": "usuario@ejemplo.com",
        "created_at": "2026-04-23 11:07:00"
    }
}
```

**Post no encontrado — 404**
```json
{
    "error": "Publicación no encontrada."
}
```

---

### 🔒 POST `/api/posts.php` — Crear un post

Usa `multipart/form-data` para poder enviar la imagen.

**Request**
```
POST http://localhost/Prueba tecnica/api/posts.php
Authorization: Basic YWRtaW46QWRtaW4xMjM0
Content-Type: multipart/form-data
```

| Campo | Tipo | Descripción |
|-------|------|-------------|
| title | text | Título del post (obligatorio) |
| content | text | Contenido del post (obligatorio) |
| email | text | Correo electrónico (obligatorio) |
| image | file | Imagen JPG, PNG o GIF — máx. 2MB (obligatorio) |

**Respuesta exitosa — 201**
```json
{
    "success": true,
    "message": "Publicación creada correctamente."
}
```

**Error de validación — 422**
```json
{
    "success": false,
    "errors": [
        "El campo title es obligatorio.",
        "La imagen es obligatoria."
    ]
}
```

---

### 🔒 DELETE `/api/posts.php?id=1` — Eliminar un post

Elimina el post y su imagen del servidor.

**Request**
```
DELETE http://localhost/Prueba tecnica/api/posts.php?id=1
Authorization: Basic YWRtaW46QWRtaW4xMjM0
```

**Respuesta exitosa — 200**
```json
{
    "success": true,
    "message": "Publicación eliminada correctamente."
}
```

**Post no encontrado — 404**
```json
{
    "error": "Publicación no encontrada."
}
```

---

### Respuestas de error generales

| Código | Descripción |
|--------|-------------|
| 401 | Sin autenticación o credenciales incorrectas |
| 404 | Recurso no encontrado |
| 405 | Método HTTP no permitido |
| 422 | Error de validación en los datos enviados |
| 500 | Error interno del servidor |

---

## Consultas SQL

Los archivos están en la carpeta `database/`:

- `query1_productos_mas_vendidos.sql` — productos ordenados del más vendido al menos vendido
- `query2_ventas_por_cliente.sql` — ventas por cliente con consecutivos agrupados, ordenado por fecha y total

## Características

- ✅ Blog público con formulario AJAX (sin recarga de página)
- ✅ Captcha reCAPTCHA anti-bot
- ✅ Validación de campos en frontend y backend
- ✅ Imágenes guardadas como archivo físico en servidor
- ✅ Panel administrativo protegido por sesión
- ✅ API REST con autenticación Basic Auth
- ✅ Contraseñas hasheadas con bcrypt
- ✅ Protección contra inyección SQL con PDO prepared statements
- ✅ Consultas SQL con JOINs y GROUP_CONCAT

## Autor

Cristian Sierra