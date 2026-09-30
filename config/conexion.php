<?php

define('DB_HOST', '127.0.0.1');
define('DB_PUERTO', '3307');
define('DB_USUARIO', 'root');
define('DB_CLAVE', '');
define('DB_NOMBRE', 'estructura_db');

function conectar(): PDO
{
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PUERTO . ";charset=utf8mb4", DB_USUARIO, DB_CLAVE);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NOMBRE . " CHARACTER SET utf8mb4");
    $pdo->exec("USE " . DB_NOMBRE);

    $pdo->exec("CREATE TABLE IF NOT EXISTS estudiantes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        codigo VARCHAR(20) NOT NULL UNIQUE,
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        fecha_nacimiento DATE NOT NULL,
        genero CHAR(1) NOT NULL,
        posicion INT NOT NULL
    )");

    return $pdo;
}