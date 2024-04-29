	<?php
	//require_once('seguridad.php');
	require_once('Connections/db.php');
	if (!isset($_SESSION)) {
			session_start();
		}
		
	mysql_select_db($database_turnos, $turnos);
	@mysql_query("SET collation_connection = utf8_general_ci;");
	mysql_query ("SET NAMES 'utf8'");
	$_SESSION["TABLE"]="turnosfull";
	$_SESSION["TABLE2"]="turnosfull";
	$fecha_inicio='';
	if(isset($_POST['fecha_inicio']) && $_POST['fecha_inicio']!=''){
	$fecha_inicio=$_POST['fecha_inicio'];
	}

	$fecha_fin='';
	if(isset($_POST['fecha_fin']) && $_POST['fecha_fin']!=''){
	$fecha_fin=$_POST['fecha_fin'];
	}

    $_SESSION["check_todos"]='';
	if(isset($_POST['check_todos']) && $_POST['check_todos']!=''){
	$_SESSION["check_todos"]=$_POST['check_todos'];
	}
	
    $_SESSION["servicio"]='';
	$servicio='';
	if(isset($_POST['servicio']) && $_POST['servicio']!=''){
	$servicio=$_POST['servicio'];
	$_SESSION["servicio"]=$servicio;
	}
	
    $_SESSION["check_servicios"]='';
	if(isset($_POST['check_servicios']) && $_POST['check_servicios']!=''){
	$_SESSION["check_servicios"]=$_POST['check_servicios'];
	}

    $_SESSION["check_am"]='';
	if(isset($_POST['check_am']) && $_POST['check_am']!=''){
	$_SESSION["check_am"]=$_POST['check_am'];
	}

    $_SESSION["check_pm"]='';
	if(isset($_POST['check_pm']) && $_POST['check_pm']!=''){
	$_SESSION["check_pm"]=$_POST['check_pm'];
	}	
	
    $_SESSION["check_apoyo"]='';
	if(isset($_POST['check_apoyo']) && $_POST['check_apoyo']!=''){
	$_SESSION["check_apoyo"]=$_POST['check_apoyo'];
	}	
	
    $_SESSION["check_modulo"]='';
	if(isset($_POST['check_modulo']) && $_POST['check_modulo']!=''){
	$_SESSION["check_modulo"]=$_POST['check_modulo'];
	}		

	
  $TABLE   = $_SESSION["TABLE"];
  $TABLE2  = $_SESSION["TABLE2"];

	
  $mostrar_graficas=0;
  if(isset($_GET['mostrar_graficas']) && $_GET['mostrar_graficas']!=''){
  $mostrar_graficas=$_GET['mostrar_graficas'];
  }
  $totalRows_RsReporte=0;
            $query_RsServicios="SELECT S.SERVID    CODIGO,
			                           S.SERVNOMB  NOMBRE
								 FROM servicios S";
			$RsServicios           = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
			$row_RsServicios       = mysql_fetch_assoc($RsServicios);		
			//$totalRows_RsServicios = mysql_num_rows($RsServicios);    
  
  if($fecha_inicio!='' && $fecha_fin!=''){
   $addwhere1='';
   $addwhere2='';
   if($servicio!=''){
    $addwhere1 = " and T.TURNSERV  = '".$servicio."'";  
    $addwhere2 = " and T2.TURNSERV = '".$servicio."'";  
   }
            $query_RsReporte="SELECT  COUNT(T.TURNCONS) REALIZADOS,
										   T.TURNIDUS USUARIO,
										   (SELECT P.PERSNOMB
											  FROM usuarios U,
												   personas P
											WHERE U.USUAID   = T.TURNIDUS 
											  and U.USUAIDPE = P.PERSCONS
											LIMIT 1) USUARIO_DES,
											(
											  SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE T2.TURNTURN = 1
											   AND  T2.TURNIDUS = T.TURNIDUS
											   AND  DATE(T2.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  DATE(T2.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND T2.TURNIDES = '2'
											   ".$addwhere2."
											) REALIZADOS_AM,
											(
											  SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE T2.TURNTURN = 2
											   AND  T2.TURNIDUS = T.TURNIDUS
											   AND  DATE(T2.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  DATE(T2.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND T2.TURNIDES = '2'
											   ".$addwhere2."
											) REALIZADOS_PM,
											(
											  SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE 
											   T2.TURNIDUS = T.TURNIDUS
											   AND  DATE(T2.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  DATE(T2.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND T2.TURNIDES = '4'
											   ".$addwhere2."
											) NO_ATENDIDOS,
											(
											  SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE T2.TURNTURN = 2
											   AND  T2.TURNIDAP = T.TURNIDUS
											   AND  DATE(T2.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  DATE(T2.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND  T2.TURNIDES = '2'
											   AND  T2.TURNAPOY = '1'
											   ".$addwhere2."
											) BRINDO_APOYO,
											(
											  SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE T2.TURNTURN = 2
											   AND  T2.TURNIDUS = T.TURNIDUS
											   AND  T2.TURNFECH >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  T2.TURNFEFI <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND  T2.TURNIDES = '2'
											   AND  T2.TURNAPOY = '1'
											   AND  T2.TURNIDAP != T.TURNIDUS 
											   ".$addwhere2."
											) RECIBIO_APOYO,
											(SELECT 
												   ROUND(SUM(TIME_TO_SEC(TIMEDIFF(T2.TURNFEFI, T2.TURNFECH))) / COUNT(TURNCONS)/60,2) SUMATORIA
											  FROM ".$TABLE2." T2
											 WHERE T2.TURNIDES = 2
											   AND  DATE(T2.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  DATE(T2.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')											 
											  AND  T2.TURNIDUS = T.TURNIDUS
											 ) PROMEDIO_TURNO
											
									   FROM  ".$TABLE." T LEFT JOIN USUARIOS U
									     ON T.TURNIDUS = U.USUAID
									 WHERE DATE(T.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									   and DATE(T.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
									   AND T.TURNIDES = '2'
									   ".$addwhere1."
									 GROUP BY T.TURNIDUS
									 order by TURNIDUS";
									//ECHO $query_RsReporte;
 			$RsReporte           = mysql_query($query_RsReporte, $turnos) or die(mysql_error());
			$row_RsReporte       = mysql_fetch_assoc($RsReporte);		
			$totalRows_RsReporte = mysql_num_rows($RsReporte);  
  
  }
  
	if($fecha_inicio !='' && $fecha_fin !='' && $mostrar_graficas==1){
			$query_RsDatosGrafica="SELECT  COUNT(T.TURNCONS),
										   T.TURNIDUS USUARIO,
										   (SELECT P.PERSNOMB
											  FROM usuarios U,
												   personas P
											WHERE U.USUAID   = T.TURNIDUS 
											  and U.USUAIDPE = P.PERSCONS
											LIMIT 1) USUARIO_DES									   
									   FROM  ".$TABLE." T
									 WHERE DATE(T.TURNFECH) >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									   and DATE(T.TURNFEFI) <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
									   AND T.TURNIDES = '2'
									 GROUP BY T.TURNIDUS
									 order by TURNIDUS
								";
								//echo($query_RsDatosGrafica);
			$RsDatosGrafica           = mysql_query($query_RsDatosGrafica, $turnos) or die(mysql_error());
			$row_RsDatosGrafica       = mysql_fetch_assoc($RsDatosGrafica);		
			$totalRows_RsDatosGrafica = mysql_num_rows($RsDatosGrafica);    
	}



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Estadistica</title>
<!-- SET: FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<meta name="viewport" content="width=xxx" />
<!-- END: FAVICON -->
<!-- SET: STYLESHEET -->
<?php /*<link href="../css/style.css" rel="stylesheet" type="text/css" media="all" />*/?>
<link rel="stylesheet" type="text/css" href="css/jquery.ui.css"/>
<script type="text/javascript" src="jquery/jquery.1.7.2.js"></script>
<script type="text/javascript" src="jquery/jquery.ui.1.8.16.js"></script>

<style type="text/css">
/*body{
 background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #003A57), color-stop(1, #267BA3) );
 background:-moz-linear-gradient( center top, #003A57 5%, #267BA3 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#003A57', endColorstr='#267BA3');
 background-color:#003A57; 
}
*/
.continer {
width: 100%;
line-height: 18px;
text-align: left;
}

.header{
background: url(header.png) repeat-x bottom;
border-bottom: 1px solid rgb(150, 150, 150);
color:white;
}
.content{
min-height:450px;
}
.marco1{
    /*border: 4px outset #78C23D;
 	border-color:#78C23D;*/
}
.marco1 td {
border-top:1 px solid #ccc;
}

.headerlabel {
background: -webkit-gradient( linear, left top, left bottom, color-stop(0.05, #003A57), color-stop(1, #00557F) );
background: -moz-linear-gradient( center top, #003A57 5%, #00557F 100% );
filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#003A57', endColorstr='#00557F');
background-color: #003A57;
color: #FFFFFF;
font-size: 11px;
font-weight: bold;
border-left: 1px solid #0070A8;
}

.Titulo5 {
	FONT-SIZE: 11px;
	COLOR: #FFFFFF;
	FONT-FAMILY: Arial, Helvetica, Verdana;
	BACKGROUND-COLOR: #666666;
	font-weight : bold;
	background-image: url(../com/img/FondoGris.gif);
}
.alt{
background: #E1EEf4; color: #00557F; 
font-size: 11px;
font-weight: normal;
/*border:solid 1px black;*/
}
.alt2{
background: #ffffff; color: #00557F; 
font-size: 11px;
font-weight: normal;
}

.datagrid {
font: normal 12px/150% Arial, Helvetica, sans-serif;
background: #fff;
overflow: hidden;
/*border: 2px solid #005A87;*/
-webkit-border-radius: 3px;
-moz-border-radius: 3px;
border-radius: 3px;
border-collapse: collapse;
}

.datagrid  td, .datagrid  th {
    padding: 3px 2px;
}
.datagrid td ul{
   list-style: none outside none;
    margin: 0;
    padding: 0;
    text-align: right;
}
.datagrid td ul li{
display: inline;
}
 .datagrid li a {
 text-decoration: none; display: inline-block;
 padding: 2px 8px;
 margin: 1px;color: #FFFFFF;
 border: 1px solid #006699;
 -webkit-border-radius: 3px;
 -moz-border-radius: 3px;
 border-radius: 3px;
 background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );
 background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
 background-color:#006699; 
 }

.datagrid  ul.active, .datagrid  ul a:hover { 
 text-decoration: none;
 border-color: #00557F;
 color: #FFFFFF;
 background: none;
 background-color:#003A57;
 }
 #tabla_graficos td{
 /*display:block;*/
 }
 
 .clear{
 height: 5px;
line-height: 0;
clear: both;
 }
/*
#headerlogo {
width: 161px;
padding: 10px 15px 2px;
float: left;
}

#headerlogo img {
display: inline;
}
*/ 
 #toptabs {
padding-top: 7px;
float: left;
}

#toptabs ul {
padding: 0;
}

#toptabs ul li {
list-style-type: none;
float: left;
}

#toptabs ul li a {
color: white;
text-shadow: rgb(50, 50, 50) 0 1px 0;
font-weight: bold;
text-decoration:none;
margin: 0 5px 0 15px;
outline: none;
}

.texto_azul{
font-size: 15px;
color:steelblue;
font-weight: bold;
}

.texto_gris{
font: 12px/17px arial, sans-serif;
color: rgb(100, 100, 100);
padding-right: 8px !important;
vertical-align: top;
}
.divtabla_izq{
background:#F2EFEF;
border-right: 4px inset #7FA5B7;
border-bottom: 4px inset #7FA5B7;
border-radius:7px;
}
.texto_entradas{
font: 12px arial,sans-serif;
color: rgb(50, 50, 50);

}
.bttn1{
background:#F2EFEF;
}
.divgr{
width:450px;
float:left;
}
</style>
<script>
$(function() {
	jQuery(function($){
	   $.datepicker.regional['es'] = {
		  closeText: 'Cerrar',
		  prevText: '<Ant',
		  nextText: 'Sig>',
		  currentText: 'Hoy',
		  monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		  monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		  dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		  dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		  dayNamesMin: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		  weekHeader: 'Sm',
		  dateFormat: 'dd/mm/yy',
		  firstDay: 1,
		  isRTL: false,
		  showMonthAfterYear: false,
		  yearSuffix: ''};
	   $.datepicker.setDefaults($.datepicker.regional['es']);
	});	

		$("#fecha_inicio,#fecha_fin").datepicker({
		   showOn: 'both',
		   buttonImage: 'calendar.png',
		   buttonImageOnly: true,
		   changeYear: true,
		   dateFormat: 'dd/mm/yy',
		   //regional:'es',
		   //numberOfMonths: 2,
		   onSelect: function(fech, objDatepicker){
			  //alert("fecha seleccionada: " + fech);
		   }
		});		

	$('#check_todos').click(function(){
	    
		if(document.getElementById('check_todos').checked==true){
			$('.group_checkbox').each(function(index){
			 id=$(this).attr('id');
			 document.getElementById(''+id).checked=true;
			});
		}else{
			$('.group_checkbox').each(function(index){
			 id=$(this).attr('id');
			 document.getElementById(''+id).checked=false;
			});
		}
	});	

		$('.group_checkbox').click(function(){	
		 	 $('.group_checkbox').each(function(index){
			 //checked = $(this).attr('checked');
			 id      = $(this).attr('id');
             if(document.getElementById(''+id).checked==false){
			  document.getElementById('check_todos').checked=false;
			 }
			});
       });
	   
		$('.noinfo_p').click(function(){
		 $(this).css('display','none');
        });
});

function Fbuscar(){
	if($("#fecha_inicio").val()==''){
     alert('debe ingresar la fecha de inicio');
	 return false;
	}
	if($("#fecha_fin").val()==''){
     alert('debe ingresar la fecha fin');
	 return false;
	}	
	document.form1.action="index.php?mostrar_graficas=1";
}

function mostrarGraficos(){
$("#tabla_graficos").toggle();
}
function f_ShowFiltros(){
$("#tabla_izq").toggle();
}
</script>
</head>
<body>
<!-- wrapper starts -->
<div class="wrapper"> 
  <!-- Header Starts -->
  <div class="continer">
    <div class="header">
      <?php /*<div id="headerlogo"><img src="../images/img_hsf.jpg" width="81" height="35"></div>*/?>
      <div id="toptabs">
	   <ul>
	    <li><a href="#"><?php /*aqui opciones de menu duplicar las li /*?>Inicio*/?></a></li>
	   </ul>
	  </div>
      <div id="headerinfo"></div>
      <div class="clear"></div>
	</div>
    <!-- Header ends --> 
    <!-- Banner Starts -->
		  
    <div class="content" id="content">
	<div class="clear"></div>
	<table width="1000" style="margin-left:10px;">
	 <tr>
	  <td><span class="texto_azul">filtros</span></td>
	 </tr>
	</table>
	 
	 <form id="form1" name="form1" method="post" action="">
	     <table style="margin-left:10px;" border="0">
		   <tr>
		    <td width="905">
			<div class="divtabla_izq">
			 <table id="tabla_izq" style="display:block;">
			 <tr>
			  <td class="texto_gris">Fecha inicio:</td>
			  <td><input type="text" readonly name="fecha_inicio" id="fecha_inicio" class="texto_entradas" size="14" value="<?php echo($fecha_inicio);?>"></td>
			  <td rowspan="8" width="350">
			   <table align="right" border="0">
			    <tr>
				 <td class="texto_gris" colspan="10" align="left"><b>Todos <input  class="check_todos" type="checkbox" name="check_todos" id="check_todos" value="1" <?php if($_SESSION["check_todos"]==1){ echo('checked');} ?>></b></td>
				</tr>		
                <tr>
                 <td colspan="4">&nbsp;</td>
				</tr>				
			    <tr>
				 <td class="texto_gris">Turnos Por Servicio</td>
				 <td><input class="group_checkbox" type="checkbox" name="check_servicios" id="check_servicios" value="1" <?php if($_SESSION["check_servicios"]==1){ echo('checked');} ?>></td>
				<?php /* <td class="texto_gris">Turnos Por Modulo</td>
				 <td><input class="group_checkbox" type="checkbox" name="check_modulo" id="check_modulo" value="1"></td>
				 */?>
				 <td class="texto_gris">Turnos apoyo</td>
				 <td><input class="group_checkbox" type="checkbox" name="check_apoyo" id="check_apoyo" value="1" <?php if($_SESSION["check_apoyo"]==1){ echo('checked');}?>></td>				 
				</tr>				
			   <tr>
				 <td class="texto_gris">Turnos en la ma&ntilde;ana</td>
				 <td><input class="group_checkbox" type="checkbox" name="check_am" id="check_am" value="1" <?php if($_SESSION["check_am"]==1){ echo('checked');}?>></td>
				 <td class="texto_gris">Turnos en la tarde</td>
				 <td><input class="group_checkbox" type="checkbox" name="check_pm" id="check_pm" value="1" <?php if($_SESSION["check_pm"]==1){ echo('checked');}?>></td>
				</tr>
			   </table>
			  </td>
			 </tr>
			 <tr>
			  <td class="texto_gris">Fecha fin:</td>
			  <td><input type="text" readonly name="fecha_fin" id="fecha_fin" class="texto_entradas" size="14" value="<?php echo($fecha_fin);?>" ></td>			 
			 </tr>
			 <tr>
			  <td class="texto_gris">Servicio:</td>
			  <td>
			   <select name="servicio" id="servicio" class="texto_entradas">
			    <option value="">Seleccione...</option>
				<?php
				do{
				?>
				<option value="<?php echo($row_RsServicios['CODIGO']);?>"  <?php if($_SESSION["servicio"]==$row_RsServicios['CODIGO']){ echo('selected');} ?>><?php echo($row_RsServicios['NOMBRE']);?></option>
				<?php
				  }while($row_RsServicios  = mysql_fetch_assoc($RsServicios));
				?>
			   </select>
			  </td>			 
			 </tr>			 
		   <tr>
		    <td colspan="4"></td>
		   </tr>
		   <tr>
		    <td  align="right"><input type="submit" name="guardar" value="Enviar" class="bttn1" onclick="return Fbuscar();"></td>
		   </tr>
		 </table>
		 </td>
		 </tr>
		 <tr>
		 <td><input type="button" name="mostrar_filtros" id="mostrar_filtros" value="Mostrar Filtros" class="bttn1" onclick="return f_ShowFiltros();"></td>
		 </tr>
		</table>
		 <table class="datagrid" width="900" style="margin-left:10px;" >
<?php		
			if($totalRows_RsReporte>0){
			?>
		   <tr>
		    <td colspan="8">
		     <ul>
			  <li><a id="amostrar" href="javascript:mostrarGraficos()" >Graficas</a></li>
			 </ul>
			</td>
		   </tr>
		   <tr class="headerlabel">
		    <td>Usuario</td>
			<td>Total Realizados</td>
		    <td>Realizados en la mañana</td>
		    <td>Realizados en la tarde</td>
			<td>No atendidos</td>
			<td>Brindo Apoyo</td>
			<td>Recibio Apoyo</td>
			<td>promedio minutos/turno</td>
		  </tr>			
			<?php
			 $k=0;
			 do{
			   $k++;
			   if($k%2==0){
			   $estilo='alt2';
			   }else
			   $estilo='alt';
			 ?>
			 <tr class="<?php echo($estilo);?>">
			  <td><?php echo($row_RsReporte['USUARIO_DES']);?></td>
			  <td><?php echo($row_RsReporte['REALIZADOS']);?></td>
			  <td><?php echo($row_RsReporte['REALIZADOS_AM']);?></td>
			  <td><?php echo($row_RsReporte['REALIZADOS_PM']);?></td>
			  <td><?php echo($row_RsReporte['NO_ATENDIDOS']);?></td>
			  <td><?php echo($row_RsReporte['BRINDO_APOYO']);?></td>
			  <td><?php echo($row_RsReporte['RECIBIO_APOYO']);?></td>
			  <td><?php echo($row_RsReporte['PROMEDIO_TURNO']);?></td>
			 </tr>
			 <?php
			   }while($row_RsReporte       = mysql_fetch_assoc($RsReporte));
			}
			
?>		 
		 </table>		 
	
      <div class="typs">
		 	<table style="margin-left:10px;"><?php
	if($mostrar_graficas==1 && $totalRows_RsReporte>0){	
	?>
	 <tr> 
	   <td>
 	    <table BORDER="0" id="tabla_graficos" class="marco1" style="margin-top:12px;" width="900">
		  <?php
		  /*
		  <tr align="center" class="Titulo5">
		   <td>Grafica turnos por usuario</td>
		   <td>Grafica turnos en la mañana</td>
		  </tr>
		  <tr class="alt">
		   <td><?php include('turnos1.php');?></td>
		   <td><?php include('turnos2.php');?></td>
	      </tr>
		  <tr align="center" class="Titulo5" >
		   <td>Grafica turnos en la tarde</td>
		   <td>Turnos de apoyo</td>		  
		  </tr>
		  <tr class="alt">
		   <td><?php include('turnos3.php');?></td>
		   <td><?php include('turnos4.php');?></td>
	      </tr>
		  <tr align="center" class="Titulo5" >
		   <td>Grafica turnos por Servicio</td>
		  </tr>
		  <tr class="alt">
		   <td><?php include('turnos5.php');?></td>
		   <td><?php //include('aa4.php');?></td>
	      </tr>	
		  */?>
		</table>
<div id="contenedor_graficos" class="marco1" style="overflow:hidden; float:left; width:900px;">
	<div class="alt divgr" ><?php include('turnos1.php');?></div>
	<?php
	if($_SESSION["check_am"]==1){
	?>
	 <div class="alt divgr"><?php include('turnos2.php');?></div>
	<?php
	}
	?>
	<?php
	if($_SESSION["check_pm"]==1){
	?>
	 <div class="alt divgr"><?php include('turnos3.php');?></div>
	<?php
	}
	?>
	<?php
	if($_SESSION["check_apoyo"]==1){
	?>
	 <div class="alt divgr"><?php include('turnos4.php');?></div>
	<?php
	}
	?>
	<?php
	if($_SESSION["check_servicios"]==1){
	?>
	 <div class="alt divgr"><?php include('turnos5.php');?></div>
	<?php
	}
	?>
</div>		  
         
	   </td>
	 </tr>
	 <?php
	}
	if($mostrar_graficas==1 && $totalRows_RsReporte==0){
     echo('<p class="texto_gris"><span style="margin-left:15px"><b>** sin registros **</b></span></p>');	
	}
?></table>

		</form>
	   <div class="clear"></div>
      </div>
      
    </div>
    <!-- Banner End --> 
    <!-- FOOTER Starts -->
    <?php
	/*
	<div class="footer">
      <div class="footer_continer">
        <div class="footer_top">
          <div class="clear"></div>
        </div>
      </div>
      <div class="copy">
        <p>Copyright © 2014 HOSPITAL SAN FRANCISCO. All rights reserved. Design by <a >BLACK GOLD TURNOS V.1</a></p>
      </div>
    </div>
	*/
	?>
  </div>
  <!-- FOOTER END --> 
</div>
<!-- WARRPER END -->
</body>
</html>