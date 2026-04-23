# Prueba Técnica

Proyecto desarrollado en PHP con arquitectura MVC para gestión de posts y autenticación de usuarios.

## Estructura del Proyecto

```
├── app/
│   ├── Controllers/        # Controladores de la aplicación
│   └── Models/             # Modelos de datos
├── config/
│   └── database.php        # Configuración de base de datos
├── views/
│   ├── admin/              # Vistas administrativas
│   │   ├── index.php
│   │   ├── login.php
│   │   └── show.php
│   ├── blog/               # Vistas del blog
│   │   └── index.php
│   └── layouts/            # Layouts compartidos
│       ├── header.php
│       └── footer.php
├── public/
│   ├── css/                # Estilos CSS
│   ├── js/                 # Scripts JavaScript
│   ├── uploads/            # Archivos subidos
│   └── index.php           # Punto de entrada
├── api/
│   ├── auth.php            # Endpoints de autenticación
│   ├── posts.php           # Endpoints de posts
│   └── helpers.php         # Funciones auxiliares
└── database/               # Scripts de base de datos
```

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior
- XAMPP o servidor web local

## Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone <repository-url>
   ```

2. **Crear la base de datos**
   - Acceder a phpMyAdmin
   - Crear una nueva base de datos llamada `prueba_tecnica`
   - Importar los scripts SQL de la carpeta `database/` (si existen)

3. **Configurar la base de datos**
   - Editar el archivo `config/database.php`
   - Ajustar las credenciales de conexión

4. **Acceder a la aplicación**
   ```
   http://localhost/Prueba%20tecnica/public/
   ```

## Uso

### Flujo de Acceso
1. Acceder a `http://localhost/Prueba%20tecnica/public/`
2. Se abre automáticamente el **Login** (es obligatorio)
3. Ingresar credenciales de administrador
4. Acceso al **Panel Administrativo** para gestionar publicaciones

### Panel Administrativo
- Ver, crear, editar y eliminar posts
- Gestión de imágenes subidas

### Blog Público
- Ver posts publicados en `/public/blog/`

### API
- Autenticación: `/api/auth.php`
- Gestión de posts: `/api/posts.php`
- Funciones auxiliares: `/api/helpers.php`

## Configuración

### Base de Datos
Editar `config/database.php`:
```php
$host = 'localhost';
$db = 'prueba_tecnica';
$user = 'root';
$pass = '';
```

## Credenciales de Prueba

Para acceder al panel administrativo:

- **Usuario:** admin
- **Contraseña:** (123456)

## Características

- ✅ Autenticación de usuarios
- ✅ CRUD de posts
- ✅ Panel administrativo
- ✅ Blog público
- ✅ API REST
- ✅ Gestión de archivos

## Notas

- Asegúrate de que XAMPP esté corriendo
- La carpeta `public/uploads/` debe tener permisos de escritura
- Usar variables de entorno para credenciales sensibles en producción

## Autor

Prueba Técnica

