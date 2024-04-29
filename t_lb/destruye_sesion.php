<?php session_start(); 
require_once('Connections/db.php');
// mysql_select_db($database_turnos, $turnos);
// @mysql_query("SET collation_connection = utf8_general_ci;");
// mysql_query ("SET NAMES 'utf8'");
$modulo   = '';
if(isset($_GET['modulo']) && $_GET['modulo']!=''){
$modulo  = $_GET['modulo'];
}
$servicio = '';
if(isset($_GET['servicio']) && $_GET['servicio']!=''){
$servicio  = $_GET['servicio'];
}
$idusuario= '';
if(isset($_GET['idusuario']) && $_GET['idusuario']!=''){
$idusuario  = $_GET['idusuario'];
}

if($modulo!='' && $servicio!='' && $idusuario!=''){
 $query_RsServicios="UPDATE modulos SET MODUESTA = 0 WHERE MODUID = '".$modulo."' ";
 $RsServicios = mysqli_query($turnos,$query_RsServicios) ;
 
 $query_RsServicios="UPDATE usuarios SET USUAESTA = 0 WHERE USUAID	 = '".$idusuario."' ";
 $RsServicios = mysqli_query($turnos,$query_RsServicios) ; 
}
/*
		$query_RsServicios="SELECT `SERVID` CODIGO,
						        `SERVNOMB`  NOMBRE,
								`SERVTUMA`,
								`SERVTUTA`,
								`SERVCOLO` 
						FROM `servicios` 
						WHERE 1
						";
		$RsServicios = mysqli_query($turnos,$query_RsServicios) ;
		$row_RsServicios = mysql_fetch_assoc($RsServicios);		
		$totalRows_RsServicios = mysql_num_rows($RsServicios);*/

// Borramos toda la sesion
session_destroy(); 
?><SCRIPT LANGUAGE="javascript">
alert('se ha detectado inactividad en la pagina durante 15 minutos sera dirigido automaticamente a la sesion de login');
location.href = "index.php";
</SCRIPT>