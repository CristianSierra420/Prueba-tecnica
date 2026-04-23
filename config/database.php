<?php
// config/database.php
// Archivo para la conexión a la base de datos

// Datos de conexión (cámbialos si tu MySQL usa otros)
define('DB_HOST', 'localhost');
define('DB_NAME', 'prueba tecnica');
define('DB_USER', 'root');
define('DB_PASS', ''); 
define('DB_CHARSET', 'utf8mb4');

// Función que devuelve la conexión a la BD
function getDB(): PDO
{
    // Cadena de conexión
    $dsn = 'mysql:host=' . DB_HOST
         . ';dbname='    . DB_NAME
         . ';charset='   . DB_CHARSET;

    // Opciones de configuración de PDO
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // muestra errores
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // resultados como array asociativo
        PDO::ATTR_EMULATE_PREPARES => false, // más seguro en consultas preparadas
    ];

    // Retorna la conexión
    return new PDO($dsn, DB_USER, DB_PASS, $options);
}