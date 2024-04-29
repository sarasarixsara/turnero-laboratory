<?php
require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
	
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");


echo "Modulo en desarrollo Trabajamos en el";
$parametro='';
if(isset($_POST['parametro_A']) && $_POST['parametro_A']!=''){
$parametro=$_POST['parametro_A'];
}

if($parametro);
 $C_Max=5;
 
	$query_RsContadorMax="
	SELECT count( `TURNCONS` ) numero,
	MODUTUMA, 
	MODUTUTA
	FROM `turnos`
	INNER JOIN modulos ON `TURNMODU` = MODUID
	WHERE `TURNPARA` ='".$parametro."'
	AND `TURNIDES` =2";

	$RsContadorMax = mysql_query($query_RsContadorMax, $turnos) or die(mysql_error());
	$row_RsContadorMax = mysql_fetch_assoc($RsContadorMax);	
	$Contador=$row_RsContadorMax['numero'];
	
	
	if($Contador == $C_Max)
	{
	 $contador= 'A';
	 echo ($contador);
	}