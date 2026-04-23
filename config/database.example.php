<?php
// ============================================================
//  Copia este archivo como database.php y ajusta los valores.
// ============================================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'nombre_de_tu_bd');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// URL base del proyecto
define('BASE_URL', '/nombre-de-tu-carpeta/public');

function getDB(): PDO
{
    $dsn = 'mysql:host=' . DB_HOST
         . ';dbname='    . DB_NAME
         . ';charset='   . DB_CHARSET;

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    return new PDO($dsn, DB_USER, DB_PASS, $options);
}