<?php
$hostname_turnos = "localhost";
$database_turnos = "tur_cc";
$username_turnos = "root";
$password_turnos = "";
$mysqli = new mysqli($hostname_turnos, $username_turnos, $password_turnos, $database_turnos);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Establecer la codificación de caracteres
$mysqli->set_charset("utf8");

?>