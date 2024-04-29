<?php
//require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
	
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");
        
		$query_RsModulo="SELECT MODUID CODIGO,
		                        MODUNOMB NOMBRE,
								'' COLOR
						  FROM MODULOS 
						WHERE  1
						";
		$RsModulo = mysql_query($query_RsModulo, $turnos) or die(mysql_error());
		$row_RsModulo = mysql_fetch_assoc($RsModulo);		
		$totalRows_RsModulo = mysql_num_rows($RsModulo);
	
		$query_RsRol="SELECT `ROLEID`CODIGO,
		                        `ROLENOMB` NOMBRE 
								FROM `roles` 
								WHERE 1
						";
		$RsRol = mysql_query($query_RsRol, $turnos) or die(mysql_error());
		$row_RsRol = mysql_fetch_assoc($RsRol);		
		$totalRows_RsRol = mysql_num_rows($RsRol);
	
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sistema de Turnos</title>
<!-- SET: FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<meta name="viewport" content="width=xxx" />
<!-- END: FAVICON -->
<!-- SET: STYLESHEET -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- END: STYLESHEET -->
<!-- SET: SCRIPTS -->
<script type="text/javascript">
</script>
</head>
<body>
<div class="wrapper"> 
  <!-- Header Starts -->
  <div class="continer">
    <div class="header">
      <div class="logo"><a href="#">Sistema de turnos Web</a></div>
      <div id="nav">
        <ul>
          <li><a href="home.php">HOME</a></li>
          <li><a href="#">ABOUT</a></li>		  
          <li><a href="panel_control.php">USUARIOS</a></li>		 
          <li><a <a href="contador.php">CONTADOR</a></li>
          <li class="pad_last"><a href="logout.php">SALIR</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
<form name="formulario1" method="post" action="panel_control_guardar.php?tipoGuardar=Guardar">
<table  align="center" border="0">
<tr>
<td colspan="2" align="center">USUARIOS DEL SISTEMA DE TURNOS</td>
</tr>
</tr>
<tr>
<td>Nombre </td>
<td>
<input type="text" name="nombre_usuario"  value="" />
</td>
</tr>
<tr>
<td>Cedula </td>
<td>
<input type="text" name="cedula_usuario"  value="" />
</td>
</tr>
<tr>
<td>Celular </td>
<td>
<input type="text" name="celular_usuario"  value="" />
</td>
</tr>
<tr>
<td>modulo </td>
<td>
			 <select name="modulo_usuario" id="modulo">
			  <option value="">Seleccione...</option>
			<?php
			if($totalRows_RsModulo>0){
			  do{
			  ?>
			 <option value="<?php echo($row_RsModulo['CODIGO']);?>" style="background:<?php echo($row_RsModulo['COLOR']);?>"><?php echo($row_RsModulo['NOMBRE']);?></option>
			  <?php
			   }while($row_RsModulo = mysql_fetch_assoc($RsModulo));
			}
			?>
			</td>
</tr>
<tr>
<td>rol </td>
<td>
			 <select name="rol_usuario" id="modulo">
			  <option value="">Seleccione...</option>
			<?php
			if($totalRows_RsRol>0){
			  do{
			  ?>
			 <option value="<?php echo($row_RsRol['CODIGO']);?>" ><?php echo($row_RsRol['NOMBRE']);?></option>
			  <?php
			   }while($row_RsRol = mysql_fetch_assoc($RsRol));
			}
			?>
			</td>

</tr>
<tr>
<td>usuario</td>
<td>
<input type="text" name="usuario"  value="" />
</td>
</tr>
<tr>
<td>contrasena</td>
<td>
<input type="password" name="password"  value="" />
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" name="usuario_turno"  value="enviar" />
</td>
</tr>
</table>
</div>
</div>
</body>
</html>