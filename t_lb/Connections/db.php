<?php
$hostname_turnos = "localhost";
$database_turnos = "tur_lb";
$username_turnos = "root";
$password_turnos = "";


//$turnos  = mysqli_connect($hostname_turnos, $username_turnos, $password_turnos) or trigger_error(mysql_error(),E_USER_ERROR); 
 //@mysql_query("SET collation_connection = utf8_general_ci;");
 //mysql_query ("SET NAMES 'utf8'");	
//mysqli_select_db($turnos, $database_turnos);
 //mysqli_select_db($connection, DB_NAME);

$turnos = mysqli_connect($hostname_turnos, $username_turnos, $password_turnos, $database_turnos);
if (mysqli_connect_errno() !== 0) {
    echo "Error al conectar a MySQL: " . mysqli_connect_error();
    exit();
}else{
    mysqli_set_charset($turnos,"utf8");
    //mysqli_select_db($repso, $database_repso)
}
