<?php
require_once('Connections/db.php');
require_once('seguridad.php');

// variables formulario panel de control
$nombre_usuario='';
if(isset($_POST['nombre_usuario'])&&$_POST['nombre_usuario']!='')
{
$nombre_usuario=$_POST['nombre_usuario'];
}

$cedula_usuario='';
if(isset($_POST['cedula_usuario'])&&$_POST['cedula_usuario']!='')
{
$cedula_usuario=$_POST['cedula_usuario'];
}

$celular_usuario='';
if(isset($_POST['celular_usuario'])&&$_POST['celular_usuario']!='')
{
$celular_usuario=$_POST['celular_usuario'];
}

$modulo_usuario='';
if(isset($_POST['modulo_usuario'])&&$_POST['modulo_usuario']!='')
{
$modulo_usuario=$_POST['modulo_usuario'];
}
$rol_usuario='';
if(isset($_POST['rol_usuario'])&&$_POST['rol_usuario']!='')
{
$rol_usuario=$_POST['rol_usuario'];
}

$usuario='';
if(isset($_POST['usuario'])&&$_POST['usuario']!='')
{
$usuario=$_POST['usuario'];
}

$password='';
if(isset($_POST['password'])&&$_POST['password']!='')
{
$password=$_POST['password'];
}

$tipoGuardar='';
if(isset($_GET['tipoGuardar'])&&$_GET['tipoGuardar']!='')
{
$tipoGuardar=$_GET['tipoGuardar'];
}

//insertar informacion para crear usuario nuevo
 if ($tipoGuardar == 'Guardar')
{

  $query_RsInsertPersona= " 
								  INSERT INTO personas (
														PERSCONS,
														PERSCEDU,
														PERSNOMB,
														PERSNUCE
														) 
												VALUES (
														NULL,
														'".$cedula_usuario."',
														'".$nombre_usuario."',
														'".$celular_usuario."'
														)";
						//echo($query_RsInsertPersona);
	$RsInsertPersona = mysql_query($query_RsInsertPersona, $turnos) or die(mysql_error());
    
	$query_RsUltInsert = "SELECT LAST_INSERT_ID() DATO";
	$RsUltInsert = mysql_query($query_RsUltInsert, $turnos) or die(mysql_error());
	$row_RsUltInsert = mysql_fetch_assoc($RsUltInsert);
	$cod_persona = $row_RsUltInsert['DATO'];
	
	  $query_RsInsertUsuario= " INSERT INTO usuarios (
	                                                   USUAID,
													   USUANOMB,
													   USUACONT,
													   USUAIDPE,
													   USUAIDMO,
													   USUAIDRO
													   )
													   VALUES 
													   (
													   NULL,
													   '".$usuario."',
													   '".$password."',
													   '".$cod_persona."',
													   '".$modulo_usuario."',
													   '".$rol_usuario."'
													   )";
								  
														
						//echo($query_RsInsertPersona);
	$RsInsertUsuario = mysql_query($query_RsInsertUsuario, $turnos) or die(mysql_error());
    
	
	
	
$redireccionar = "location: logout.php";
header($redireccionar);
}
?>
