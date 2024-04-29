
//$hostname_turnos = "localhost";
//$database_turnos = "tur_lb";
//$username_turnos = "root";
//$password_turnos = "";
//$turnos  = mysql_pconnect($hostname_turnos, $username_turnos, $password_turnos) or trigger_error(mysql_error(),E_USER_ERROR); 
//mysql_query("SET collation_connection = utf8_general_ci;"); tenia arroba al inicio
//mysql_query ("SET NAMES 'utf8'");	
//mysql_select_db($database_turnos, $turnos);

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