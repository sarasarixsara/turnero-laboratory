<?php session_start(); 
require_once('Connections/db.php');
$turnos->select_db("tur_lb");
// @mysqli_query("SET collation_connection = utf8_general_ci;");
// mysqli_query ("SET NAMES 'utf8'");
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
 $query_RsServicios="UPDATE modulos SET MODUESTA = 0, MODUUSUA = '', MODUMULT = '', MODUSERV = '' WHERE MODUID = '".$modulo."' ";
 $RsServicios = mysqli_query($turnos, $query_RsServicios);
 
 $query_RsServicios="UPDATE modulos SET MODUESTA = 0, MODUUSUA = '', MODUMULT = '', MODUSERV = '' WHERE MODUUSUA = '".$idusuario."' ";
 $RsServicios = mysqli_query($turnos, $query_RsServicios);
 
 $query_RsServicios="UPDATE usuarios SET USUAESTA = 0 WHERE USUAID	 = '".$idusuario."' ";
 $RsServicios = mysqli_query($turnos, $query_RsServicios);
}
// Borramos toda la sesion
session_destroy(); 
?>
<SCRIPT LANGUAGE="javascript">
location.href = "index.php";
</SCRIPT> 
