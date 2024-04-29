<?php session_start(); 
require_once('Connections/db.php');
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");
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

if($modulo!='' /*&& $servicio!=''*/ && $idusuario!=''){
 $query_RsServicios="UPDATE modulos SET MODUESTA = '', MODUUSUA = '', MODUMULT = '', MODUSERV = '' WHERE MODUID = '".$modulo."' ";
 $RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
 
 $query_RsServicios="UPDATE modulos SET MODUESTA = '', MODUUSUA = '', MODUMULT = '', MODUSERV = '' WHERE MODUUSUA = '".$idusuario."' ";
 $RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error()); 
 
 $query_RsServicios="UPDATE usuarios SET USUAESTA = 0 WHERE USUAID	 = '".$idusuario."' ";
 $RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error()); 
}
// Borramos toda la sesion
session_destroy(); 
?>
<SCRIPT LANGUAGE="javascript">
location.href = "index.php";
</SCRIPT> 
