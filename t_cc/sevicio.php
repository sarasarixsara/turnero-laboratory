<?php
require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
	
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");
		$query_RsServicios="SELECT `SERVID` CODIGO,
						        `SERVNOMB`  NOMBRE,
								`SERVTUMA`,
								`SERVTUTA`,
								`SERVCOLO` 
						FROM `servicios` 
						WHERE 1
						";
		$RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
		$row_RsServicios = mysql_fetch_assoc($RsServicios);		
		$totalRows_RsServicios = mysql_num_rows($RsServicios);
		
		$RsServicios2 = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
		$row_RsServicios2 = mysql_fetch_assoc($RsServicios2);		
		$totalRows_RsServicios2 = mysql_num_rows($RsServicios2);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<link href="css/messages.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END: STYLESHEET -->
<!-- SET: SCRIPTS -->
<style type="text/css">
.text_servicio{
font-size:12px;
}
.SBRASER{
BACKGROUND:#F2F8F4;
}
#tableservicios{
margin: 0 auto; top:30%; position:fixed; left: 30%; background:#F8F8F8;  border: solid 1px #ccc; border-radius:3px;
box-shadow: 0 8px 15px 0 #ccc;
}
#tableservicios td{
padding-left:7px;
padding-top:5px;
}
.SBRA:hover, SBRASER:hover{
  background:#f9ebc6;
}
</style>
<script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>
<script src="js/messages.js" type="text/javascript"></script>
<script type="text/javascript">
function validar(){
   tipo_servicio   = document.getElementById('tipo_servicio').value;
   servicio        = document.getElementById('servicio').value;
   if(tipo_servicio == '')
  {
   inlineMsg('tipo_servicio','debe seleccionar el tipo de servicio.',3);
		return false;
  }
 if(document.getElementById('tipo_servicio').value=='1'){
   if(servicio==''){
        inlineMsg('servicio','debe seleccionar un servicio.',3);
		return false;
  }
  document.form_servicios.action=("tipoguardar.php?tipoguardar=servicio_crear&C_servicio="+document.getElementById('servicio').value);  
  }
 var comprobar_servicios=0;
  if(document.getElementById('tipo_servicio').value=='2'){
  //alert('servicios_check');
	  $('.servicios_check').each(function(index){
	    id=$(this).attr('id');
		if(document.getElementById(''+id).checked==true){
		 comprobar_servicios=comprobar_servicios+1;
		}
	  });
	  if(comprobar_servicios < 2){
	  inlineMsg('servicesmult','debe marcar como minimo dos servicios para entrar en modo servicio multiple.',4);
		return false;
	  }
document.form_servicios.action=("tipoguardar_multiple.php?tipoguardar=servicio_crear");  	  
  }
}
 function f_show(id){
  if(id=='1'){
   document.getElementById('seleccionar_servicio').style.display='block';
   document.getElementById('agregar_servicios').style.display='none';
  }
  if(id=='2'){
   document.getElementById('seleccionar_servicio').style.display='none';
   document.getElementById('agregar_servicios').style.display='block';
  }
  if(id==''){
   document.getElementById('seleccionar_servicio').style.display='none';
   document.getElementById('agregar_servicios').style.display='none';
  
  }
 } 
</script>
</head>
<body>
<form name="form_servicios" id="form_servicios" action="" method="post">
<table id="tableservicios" border="0" width="400">
<tr>
 <td class="labeltext" width="200"><b>Tipo de servicio</b></td>
 <td>
  <select name="tipo_servicio" id="tipo_servicio" onchange="f_show(this.value);" class="inputtext">
    <option value="">Seleccione...</option>
    <option value="1">Unico Servicio</option>
    <option value="2">Servicio multiple</option>
  </select>
 </td>
 <td>&nbsp;&nbsp;</td>
 <td>
			
			<td>
		   </tr>
<tr>
 <td colspan="2">
  <table id="seleccionar_servicio" style="display:none; margin-left:60px;" border="0">
   <tr>
		    <td class="labeltext"><b>Servicio</b></td>
			<td>
			 <select name="servicio" id="servicio"  class="inputtext">
			  <option value="">Seleccione...</option>
			<?php
			if($totalRows_RsServicios>0){
			  do{
			  ?>
			 <option value="<?php echo($row_RsServicios['CODIGO']);?>" style="background:"><?php echo($row_RsServicios['NOMBRE']);?></option>
			  <?php
			   }while($row_RsServicios = mysql_fetch_assoc($RsServicios));
			}
			?>
			</td>

 </tr>
</table>
 </td>
</tr>		   
<tr>
 
			<td colspan="2">
			 <table id="agregar_servicios" style="display:none;">
			  <tr>
			   <td class="labeltext" width="200"><b>Seleccionar Servicios</b></td>
			   <td>
			    <TABLE id="servicesmult">
			  <?php
				if($totalRows_RsServicios2>0){
				  $n=0;
				  do{
				   $n++;
				   if($n%2==0){
				     $esti='SBRA';
				   }else{
				     $esti='SBRASER';
				   }
				  ?>
				  <tr class="<?php echo($esti);?>">
				    <td class="text_servicio"><?php echo($row_RsServicios2['NOMBRE']);?><td>
					<td WIDTH="50" align="center"><input type="checkbox" class="servicios_check" name="servi_<?php echo($row_RsServicios2['CODIGO']);?>" id="servi_<?php echo($row_RsServicios2['CODIGO']);?>" value="<?php echo($row_RsServicios2['CODIGO']);?>">
				    </td>
				  </tr>
				  <?php
				   }while($row_RsServicios2 = mysql_fetch_assoc($RsServicios2));
				}
			  ?>  
			   </table>
			  </td>
			  </tr>
			 </table>
			</td>
</tr>		   
		   <tr>
		    <td colspan="2" align="center"><input class="btn btn-default" type="submit" value="Iniciar Servicio" onclick="return validar();"></td>
		   </tr>
		   <tr>
		    <td><br></td>
		   </tr>
</table>
 </form>