<?php
require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
//print_r($_SESSION["ARR_SERVI"]);	
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");
/*
 campos hidden con nombre seguido de _ y el codigo para obtener nombres unicos dinamicos de acuerdo al id del servicio
paramservice_3          parametro en la tabla parametros parametrizado
paramservicevalor_3     valor que se ingreso como parametro en la tabla parametros parametrizado
secuenciaservice_3      secuencia del servicio tabla turnos turncoas
secuencia_3             valor que es uno o dos
consecutivoturno_3      es el consecutivo de la tabla turnos turncons
*/
//print_r($_SESSION["ARR_SERVI"]);

$varios_servicios='';
for($i=0; $i<count($_SESSION["ARR_SERVI"]); $i++){
  $varios_servicios=$varios_servicios.','.$_SESSION["ARR_SERVI"][$i];
}
$tamano_servicios=strlen($varios_servicios);
$serviciosmult=substr($varios_servicios,1,$tamano_servicios);

$rol_usuario =$_SESSION["ROL"];

$inicio_turno='';
$cupo_turno='';
$jornada=1;
$turno_sincronizado="";
/*if(isset($_SESSION["SINCRONIZADO"])){
$turno_sincronizado=$_SESSION["SINCRONIZADO"];
}

echo("este es el turno sincronizado ".$turno_sincronizado);
*/
/*
		$query_RsServicios="SELECT `SERVID` CODIGO,
						        `SERVNOMB`  NOMBRE,
								`SERVTUMA`,
								`SERVTUTA`,
								`SERVCOLO` 
						FROM `servicios` 
						WHERE 1
						AND SERVID != '".$_SESSION["ID_SERVI"]."'
						";
		$RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
		$row_RsServicios = mysql_fetch_assoc($RsServicios);		
		$totalRows_RsServicios = mysql_num_rows($RsServicios);
*/
/*
        $query_RsDatosBienvenida="SELECT M.MODUNOMB MODULO_DES,
		                           (
								    SELECT S.SERVNOMB
								      FROM servicios S
									 WHERE S.SERVID = '".$_SESSION["ID_SERVI"]."'
								   ) SERVICIO_DES
		                            FROM modulos M
								   where MODUID = '".$_SESSION["MODULO"]."'
								  ";
		$RsDatosBienvenida = mysql_query($query_RsDatosBienvenida, $turnos) or die(mysql_error());
		$row_RsDatosBienvenida = mysql_fetch_assoc($RsDatosBienvenida);
		
		
		$query_RsFechaActual="SELECT SYSDATE() FECHA,
							  DATE_FORMAT(SYSDATE(), '%p') HORA,
							  (SELECT M.SERVTUMA
							    FROM SERVICIOS M
							   WHERE M.SERVID = '".$_SESSION['ID_SERVI']."') MANANA,
							   (SELECT M2.SERVTUTA
							    FROM SERVICIOS M2
							   WHERE M2.SERVID = '".$_SESSION['ID_SERVI']."') TARDE
							  
						";
		$RsFechaActual = mysql_query($query_RsFechaActual, $turnos) or die(mysql_error());
		$row_RsFechaActual = mysql_fetch_assoc($RsFechaActual);		
		//echo('('.$row_RsFechaActual['FECHA'].')');
		//$totalRows_RsFechaActual = mysql_num_rows($RsFechaActual);
	//echo($_SESSION["MODULO"]);
			 if($row_RsFechaActual['HORA']=='AM'){
               $inicio_turno='08:00:00 AM';	
               $cupo_turno=$row_RsFechaActual['MANANA'];
			   $jornada=1;
 			 }
			 if($row_RsFechaActual['HORA']=='PM'){
               $inicio_turno='2:00:00 PM';			 
			   $cupo_turno=$row_RsFechaActual['TARDE'];
			   $jornada=2;
 			 }
*/	
		$query_RsParams="SELECT A.PARAVALOR NODESERVER,
                           (SELECT A2.PARAVALOR 					
						  FROM params_app A2 
						WHERE A2.PARACODI = '2') NODEPORT		
						  FROM params_app A 
						WHERE A.PARACODI = '1' 
						
						";
		$RsParams = mysql_query($query_RsParams, $turnos) or die(mysql_error());
		$row_RsParams = mysql_fetch_assoc($RsParams);	
        $nodeserver = $row_RsParams['NODESERVER'];		
        $nodeport   = $row_RsParams['NODEPORT'];

		$query_RsFechaActual="SELECT SYSDATE() FECHA,
							  DATE_FORMAT(SYSDATE(), '%p') HORA
							  
						";
		$RsFechaActual = mysql_query($query_RsFechaActual, $turnos) or die(mysql_error());
		$row_RsFechaActual = mysql_fetch_assoc($RsFechaActual);		
		
			 if($row_RsFechaActual['HORA']=='AM'){
               $inicio_turno='TURNOS AM';	
			   $jornada=1;
 			 }
			 if($row_RsFechaActual['HORA']=='PM'){
               $inicio_turno='TURNOS PM';			 
			   $jornada=2;
 			 }	
function ArmarLi($arr,$turnos){ //$turnos es el resource de conexion
 if(is_array($arr)){
   for($i=0; $i<count($arr); $i++){
			$query_RsServicios="SELECT * FROM servicios where SERVID = '".$arr[$i]."'";
			$RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
			$row_RsServicios = mysql_fetch_assoc($RsServicios);		
			$totalRows_RsServicios = mysql_num_rows($RsServicios);
			 if($totalRows_RsServicios>0){
			  echo(/*$row_RsServicios['SERVID'].*/'<div class="s_servicios" id="s_servicios_'.$row_RsServicios['SERVID'].'">'.$row_RsServicios['SERVNOMB'].'&nbsp;&nbsp;&nbsp;<input type="text" name="paramservice_'.$row_RsServicios['SERVID'].'" id="paramservice_'.$row_RsServicios['SERVID'].'" value="" size="3"><input type="text" name="paramservicevalor_'.$row_RsServicios['SERVID'].'" id="paramservicevalor_'.$row_RsServicios['SERVID'].'" value="" size="3"><input type="text" name="secuenciaservice_'.$row_RsServicios['SERVID'].'" id="secuenciaservice_'.$row_RsServicios['SERVID'].'" value="" size="3"><input type="text" name="secuencia_'.$row_RsServicios['SERVID'].'" id="secuencia_'.$row_RsServicios['SERVID'].'" value="" size="3"><input type="text" name="consecutivoturno_'.$row_RsServicios['SERVID'].'" id="consecutivoturno_'.$row_RsServicios['SERVID'].'" value="" size="3"><input class="checkservices" type="checkbox" value="'.$row_RsServicios['SERVID'].'" name="checkserv_'.$row_RsServicios['SERVID'].'" id="checkserv_'.$row_RsServicios['SERVID'].'"></div>'); 
			 }
    }
 }
}
			 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Turnos</title>
<!-- SET: FAVICON -->
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
<meta name="viewport" content="width=xxx" />
<!-- END: FAVICON -->
<!-- SET: STYLESHEET -->
<script src="jquery/jquery-1.10.2.min.js"></script>
<script src="socket/socket.io.min.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END: STYLESHEET -->
<!-- SET: SCRIPTS -->
<style type="text/css">
body{
 background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #003A57), color-stop(1, #267BA3) );
 background:-moz-linear-gradient( center top, #003A57 5%, #267BA3 100% );
 filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#003A57', endColorstr='#267BA3');
 background-color:#003A57; 
}
.sincronizadosi{
background: url(images/validated.png) no-repeat;
 margin: 0 auto 0 50%;
}
.s_servicios{
border-bottom:solid 1px #2F93A4;
line-height:1.7;
/*text-align:right;*/
padding-left:20px;
}
.s_servicios:hover{
background:#C3CCED;
cursor:pointer;
}
.servicio_atendido{
font-size:30px;
}

.inputsecuencia{
    background: #E3DEDE;
    border: 2px solid #ccc;
    color: #0E0E0E;
    font-weight: bold;
    text-align: center;
	font-size:18px;
}
#aviso_seleccionado a{
font-size:15px;
font-weight:bold;
color:#ff0000;
}
#aviso_seleccionado a:hover{
color:blue;
}
.info {
 background: none repeat scroll 0 0 #F8F8F8;
    border: 1px solid #CCCCCC;
    color: #000000;
    font: 12px/150% Arial,Helvetica,sans-serif;
    min-height: 70px;
    margin-left: 8px;
    margin-top: -19px;
    width: 928px;
	padding-bottom:15px;
}

.info_text {
margin: 5px;
font-family: sans-serif;
font-size: 13px;
}
.contentayuda{
    background: none repeat scroll 0 0 #FFFFFF;
    float: left;
    margin-left: auto;
    margin-right: auto;
    padding-bottom: 10px;
    padding-top: 20px;
    width: 978px;
	/*border:solid 1px #ff0000;*/
}

.ayuda{
   background: none repeat scroll 0 0 #FFFFFF;
    float: left;
    padding-bottom: 10px;
    padding-top: 20px;
    width: 350px;
	min-height: 300px;
	overflow:hidden;
	margin-left:20px;
	/*border: 1px solid #005A87;*/
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
}
.alt2{
background: #ffffff; color: #00557F; 
font-size: 11px;
font-weight: normal;
}

#num_atendidosAyuda {
margin-left: 50%;
font-weight: bold;
color: #000000;
font-size: 85px;
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
 .bienvenida{
 background: none repeat scroll 0 0 #F8F8F8;
    font: 12px/150% Arial,Helvetica,sans-serif;
    margin-left: 28px;
    margin-top: 5px;
    width: 930px;
	color:#D9536C;
 
 }
 .contentmultiple{
 margin-top:1px; 
 margin-left:27px; 
 clear:both;
 background:#E1EEF4;
 width:930px;
 text-align:right;
 }
 .div_titleserv{
 background-color:#dd7900;
 font-family:georgia,serif;
 font-weight:normal;
 font-style:italic;
 font-size:12px;
 color:#ffffff;
 padding:10px 5px;
 }
 .ul_servicios{
  
 }
 #aviso_seleccionado b{
 font-size:16px;
 }
 .letra_turno{
  border:solid 1px white;
  text-align:center;
  font-weight: bold;
 }
</style>
<?php /*<script type="text/javascript" src="jquery/jquery-1.9.1.js"></script>*/?>
<script>
var socket;
$(document).ready(function() {
 manageSessions.unset("logueados");
                 socket = new io.connect('<?php echo($nodeserver);?>', {
                    port: <?php echo($nodeport);?>
                });
manageSessions.set("logueados", '<?php echo($_SESSION["IDUSU"]);?>|<?php echo($_SESSION["USU_AUT_NOMB"]);?>|<?php echo($_SESSION["MODULO"]);?>|<?php echo($_SESSION["NOMBRE_MODULO"]);?>|<?php echo($serviciosmult);?>|<?php echo($_SESSION["SALA"]);?>|1');	
socket.emit("UsuariosLogueados", manageSessions.get("logueados"));	
                socket.on('disconnect', function() {
                    console.log('sin conexion');
                });	
    //si el usuario está en uso lanzamos el evento userInUse y mostramos el mensaje
			socket.on("userInUse", function()
			{
			    //alert('el usuario que intenta acceder ya esta en uso');
				manageSessions.unset("logueados");
				return; 
			});
			
			
    socket.on("MostrarUsuarios", function(usersOnline)
    {			
	 tablatitulos="<tr><td width='150'><b>Persona</b></td><td><b>Modulo</b></td></tr>";
        //limpiamos el sidebar donde almacenamos usuarios
        $("#listausuarios").html("");
        $("#listausuarios").append(tablatitulos);       
	   //si hay usuarios conectados, para evitar errores
        if(!isEmptyObject(usersOnline))
        {
            //recorremos el objeto y los mostramos en el sidebar, los datos
            //están almacenados con {clave : valor}
            $.each(usersOnline, function(key, val)
            {
			  data=val.split('|');
			  nombre         = data[1];
			  modulo_nombre  = data[3];
			  sala           = data[4];
			  if (sala==2) {
			     datostrtd="<tr><td>"+nombre+"</td><td>"+modulo_nombre+"</td></tr>";
                 $("#listausuarios").append(datostrtd);
               }
            })
        }
    });		
				

				
});

//objeto para el manejo de sesiones
var manageSessions = {
    //obtenemos una sesión //getter
    get: function(key) {
        return sessionStorage.getItem(key);
    },
    //creamos una sesión //setter
    set: function(key, val) {
        return sessionStorage.setItem(key, val);
    },
    //limpiamos una sesión
    unset: function(key) {
        return sessionStorage.removeItem(key);
    }
};

//función que comprueba si un objeto está vacio, devuelve un boolean
function isEmptyObject(obj) 
{
    var name;
    for (name in obj) 
    {
        return false;
    }
    return true;
}

window.onload = function(){mantenerSesion();}

function mantenerSesion(){
 	  setTimeout(function() {
						 try{
      var date = new Date();
      var timestamp = date.getTime();	  

        var v_dato = getDataServer("tipoguardar.php","?tipoguardar=mantenerSesion");							 
		if(v_dato=='listo'){
		  mantenerSesion2();
		}
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 600000 );
}
function mantenerSesion2(){
 	  setTimeout(function() {
						 try{
      var date = new Date();
      var timestamp = date.getTime();	  

        var v_dato = getDataServer("tipoguardar.php","?tipoguardar=mantenerSesion");							 
		if(v_dato=='listo'){
		  mantenerSesion();
		}
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 600000 );
}

/*
var cadenaLibros = '[{"Titulo": "El señor de los anillos", "Autor": "J.R.R. Tolkien"},{"Titulo": "Cancion de hielo y fuego", "Autor": "George RR Martin"},{"Titulo": "Los Pilares de la Tierra", "Autor": "Ken Follett"}]';  
  
var libros = JSON.parse(cadenaLibros);  */
/*
for(var i = 0; i < libros.length; i++ )  
  alert('El libro: ' + libros[i].Titulo + ' es del autor: ' + libros[i].Autor);  
  */
  
 param_sincronizados =new Array();
marcado=0;
turno_pendiente=0;
$(function() {
		$('.s_servicios').click(function(index){
		 datos = $(this).attr('id').split('_');
		 cod   = datos[2]; <?php /*cod id del servicio que se da click*/?>
		 servicio_activo=$('#accion_turnero').val().split('|'); <?php /* servicio activo  es la variable que contiene el servicio actual si se esta llevando alguna accion */?>
		 /*if(document.getElementById('checkserv_'+cod).disabled==''){
		 document.getElementById('checkserv_'+cod).checked=true;
		 }*/
		 //if(marcado==0){
		 //alert('servicio activo = '+servicio_activo[1]);
		 //alert('codigo marcado = '+cod);
			 marcado=1;
			 if(document.getElementById('secuenciaservice_'+cod).value !=''){
              //document.getElementById('checkserv_'+cod).checked=true;
			  $('#li_sincronizar').css('display','block');
			  $('#T_sincronizado').css('display','none');
			  $('#servicio_atendido').text($('#s_servicios_'+cod).text());
			  $('#num_atendidos').text($('#secuenciaservice_'+cod).val());
			   if($('#accion_turnero').val()=='0|0'){
			      $('#li_turno').css('display','block');
			   }
			  if(servicio_activo[1]==cod){ 
			    if(servicio_activo[0]==0){ <?php /*servicio_activo[0]=0 esta el boton llamar si es 1 esta inicio siguiente si es 2 fin*/?>
			     $('#li_turno').css('display','block');
				 turno_pendiente=0;
				 }
				 if(servicio_activo[0]==1){
			      $('#li_turno').css('display','none');
			      $('#li_fin').css('display','none');
				  $('#li_inicio').css('display','block');
				  $('#li_sig').css('display','block');
				 }
				 if(servicio_activo[0]==2){
			      $('#li_turno').css('display','none');
			      $('#li_fin').css('display','block');
				  $('#li_inicio').css('display','none');
				  $('#li_sig').css('display','none');
				  turno_pendiente=1;
				 }
				 if(turno_pendiente==0){
				 document.getElementById('checkserv_'+cod).checked=true;
				 }
			  }else{
			    if(servicio_activo[0]==0){ <?php /*servicio_activo[0]=0 esta el boton llamar si es 1 esta inicio siguiente si es 2 fin*/?>
			     $('#li_turno').css('display','block');
				 turno_pendiente=0;
				 }
				 if(servicio_activo[0]==1){
			      $('#li_turno').css('display','none');
				  $('#li_fin').css('display','none');
				  //$('#li_inicio').css('display','none');
				  //$('#li_sig').css('display','none');
				  $('#li_inicio').css('display','block');
				  $('#li_sig').css('display','block');
				  //document.getElementById('checkserv_'+cod).checked=false;
				  alert('tiene un turno que ha sido llamado a ventanilla en el servicio '+$('#s_servicios_'+servicio_activo[1]).text());
				  $('#num_atendidos').text($('#letraturno_'+servicio_activo[1]).val()+$('#secuenciaservice_'+servicio_activo[1]).val());
				  $('#servicio_atendido').text($('#s_servicios_'+servicio_activo[1]).text());
				  turno_pendiente=1;
				 }
				 if(servicio_activo[0]==2){
			      $('#li_turno').css('display','none');
			      $('#li_fin').css('display','block');
				  $('#li_inicio').css('display','none');
				  $('#li_sig').css('display','none');
				  //document.getElementById('checkserv_'+cod).checked=false;
				  alert('tiene un turno por finalizar al servicio '+$('#s_servicios_'+servicio_activo[1]).text());
				  $('#num_atendidos').text($('#secuenciaservice_'+servicio_activo[1]).val());
				  $('#servicio_atendido').text($('#s_servicios_'+servicio_activo[1]).text());
				  turno_pendiente=1;
				  }
                  if(turno_pendiente==1){
				  document.getElementById('checkserv_'+cod).checked=false;
				  }				  
			  
			  }
			 
			 
			 }else{
			 $('#servicio_atendido').text('');
			 $('#li_turno').css('display','none');
			 $('#li_sincronizar').css('display','none');
			 }

			 $('#servicio_activo').val(cod);
			 $('.s_servicios').each(function(index){
			   des  = $(this).attr('id').split('_');
			   cod2 = des[2];
			   if(turno_pendiente==0){
				   if(cod!=cod2){
					//document.getElementById('secuenciaservice_'+cod2).value='disabled';
					//document.getElementById('checkserv_'+cod2).disabled='disabled';
					document.getElementById('checkserv_'+cod2).checked=false;
				   }else{
				   document.getElementById('checkserv_'+cod2).checked=true;
					 if(document.getElementById('secuenciaservice_'+cod).value==''){
					   $('#aviso_seleccionado').text(''); 
					   $('#num_atendidos').text('T');
					   $('#aviso_seleccionado').append('has seleccionado el servicio<b>'+$('#s_servicios_'+cod).text()+'</b>ya puedes sincronizar el turno ');
					   //$('#aviso_seleccionado').append('<a href="javascript:reiniciarchecks();">AQUI</a>');
					   mostrar();
					 }else{
					 $('#aviso_seleccionado').text('');
					 if(turno_pendiente==0){
					 $('#num_atendidos').text($('#letraturno_'+cod).val()+$('#secuenciaservice_'+cod).val());
					 }
					 $('#aviso_seleccionado').append('has seleccionado el servicio<b>'+$('#s_servicios_'+cod).text()+'</b>ya puedes empezar a dar gestion a este servicio');
					   //$('#aviso_seleccionado').append('<a href="javascript:reiniciarchecks();">AQUI</a>');
					 }
				   }
			   }
			 });
		 //}
		 
		})

});

function todos_parametrizados(){
 var param_todos=0;
 $('.checkservices').each(function(index){
   id=$(this).attr('id');
   codigo=id.split('_');
   if($('#secuenciaservice_'+codigo).val()==''){
   param_todos=1;
   }
 });
 return param_todos;
}


function reiniciarchecks(){
marcado=0;
$('#servicio_activo').val('');
$('#aviso_seleccionado').text('');
 $('.s_servicios').each(function(index){
 		 datos = $(this).attr('id').split('_');
		 cod   = datos[2];
		 document.getElementById('checkserv_'+cod).checked=false;
		 document.getElementById('checkserv_'+cod).disabled='';
 })
 ocultar();
}
window.onload = function(){ mantenerSesion();}

function mantenerSesion(){
 	  setTimeout(function() {
						 try{
      var date = new Date();
      var timestamp = date.getTime();	  

        var v_dato = getDataServer("tipoguardar.php","?tipoguardar=mantenerSesion");							 
		if(v_dato=='listo'){
		  mantenerSesion2();
		}
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 600000 );

}
function mantenerSesion2(){
 	  setTimeout(function() {
						 try{
      var date = new Date();
      var timestamp = date.getTime();	  

        var v_dato = getDataServer("tipoguardar.php","?tipoguardar=mantenerSesion");							 
		if(v_dato=='listo'){
		  mantenerSesion();
		}
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 600000 );
}

</script>
<script type="text/javascript">
document.onkeydown=function (e)
{
if(e)
document.onkeypress=function (){return true;}

var evt=e?e:event;
if(evt.keyCode==116)
{
if(e)
document.onkeypess=function(){return false;}
else
{
evt.keyCode=0;
evt.returnValue=false;
}
}
}
 

	function getDataServer(url, vars){
		 var xml = null;
		 try{
			 xml = new ActiveXObject("Microsoft.XMLHTTP");
		 }catch(expeption){
			 xml = new XMLHttpRequest();
		 }
		 xml.open("GET",url + vars, false);
		 xml.send(null);
		 if(xml.status == 404) alert("Url no valida");
		 return xml.responseText;
	}
	
function getXMLHttpRequestAyuda() {
    var aVersions = [ "MSXML2.XMLHttp.5.0","MSXML2.XMLHttp.4.0","MSXML2.XMLHttp.3.0","MSXML2.XMLHttp","Microsoft.XMLHttp"];
    if (window.XMLHttpRequest) {
        // para IE7, Mozilla, Safari, etc: que usen el objeto nativo
        return new XMLHttpRequest();
    }
    else if (window.ActiveXObject) {
        // de lo contrario utilizar el control ActiveX para IE5.x y IE6.x
        for(var i=0;i<aVersions.length;i++) {
            try {
                var oXmlHttp = new ActiveXObject(aVersions);
                return oXmlHttp;
            }
            catch(error) {
                // no necesitamos hacer nada especial
            }
        }
    }
}	



function explodeAyuda(nombre_div,pagina,tipoexport) {
	var aleatorio=Math.random();
    var cont = document.getElementById(nombre_div); // aqui esta la var nombre_div
	var cont_cargue = document.getElementById('link_cargandoayuda');
    ajax = getXMLHttpRequestAyuda();

    ajax.open('GET', pagina + "?tipoguardar=ModeAyuda&modulo=<?php echo($_SESSION["MODULO"]);?>"
							+ "&tipoexport=" + tipoexport
							+ "&nocache=" + aleatorio
				, true); // aqui esta la var "pagina"

	ajax.onreadystatechange = function() {

		if(ajax.readyState == 4) {
			if(ajax.status == 200) {
				cont.innerHTML = ajax.responseText;
				cont_cargue.innerHTML = '';
			}
		}
		else {
        cont_cargue.innerHTML = '<img src="images/Procesando.gif">';
		}
	}

	ajax.send(null);
	//alert(ajax.responseText);


}

	function generarAyuda(exp)
	{
	    //explodeAusentismo('link_beneficiarios','link_beneficiarios.php');
		explodeAyuda('link_ayuda','link_ayuda.php',exp);
		
	}
	
function f_sincronizar(){
seleccionado=0;
num_sincronizado='';
cod_servicio='';
$('.checkservices').each(function(index){
 id=$(this).attr('id');
 if(document.getElementById(''+id).checked==true){
 seleccionado=1;
 part         = id.split('_');
 cod_servicio = part[1];
 }
});
if(seleccionado==0){
alert('debe seleccionar un servicio para iniciar el turnero');
return;
}
 if (document.getElementById('numero_sincronizado').value==''){
	   alert("debe ingresar el numero de Turno");
	   return;
	  }
	  
 if (isNaN(document.getElementById('numero_sincronizado').value)){
	   alert("debe ingresar un valor numerico");
	   return;
	  }	  
	  
      var date = new Date();
      var timestamp = date.getTime();	  
if(confirm('seguro que desea sincronizar este valor?')){
var v_dato = getDataServer("tipoguardar_multiple.php","?tipoguardar=Sincronizar_Turno&modulo=<?php echo($_SESSION['MODULO']); ?>&turnojornada=<?php echo($jornada);?>&servicio_sincronizado="+cod_servicio+"&numero_sincronizado="+document.getElementById('numero_sincronizado').value+"&letra_sincronizada="+document.getElementById('letra_sincronizada').value+"&time="+timestamp);
	//alert(v_dato);
if(v_dato!=''){
var v_campos;
	var caracter='/';
	
    v_campos=v_dato.split(caracter);
	
	var valor1=v_campos[0];
	/*
	document.form3.parametro_A.value =valor1;    
   	document.form3.secuencia.value='uno';
	*/
	document.getElementById('letraturno_'+cod_servicio).value=document.getElementById('letra_sincronizada').value;
	document.getElementById('secuencia_'+cod_servicio).value='uno';
	document.getElementById('paramservice_'+cod_servicio).value=valor1;
	document.getElementById('secuenciaservice_'+cod_servicio).value=document.getElementById('numero_sincronizado').value;
	document.getElementById('paramservicevalor_'+cod_servicio).value=document.getElementById('numero_sincronizado').value;
    document.getElementById('li_turno').style.display='block';
	document.getElementById('T_sincronizado').style.display='none';
	document.getElementById('dato_sincronizado').innerHTML=document.getElementById('numero_sincronizado').value;
	//document.getElementById('paramactivo_'+cod_servicio).innerHTML='Activo';
	$('#paramactivo_'+cod_servicio).addClass('sincronizadosi');
	$('#aviso_seleccionado').text('');
	 $('#aviso_seleccionado').append('estas en el servicio <b>'+$('#s_servicios_'+cod).text()+'</b>ya puedes realizar los turnos');
	   //$('#aviso_seleccionado').append('<a href="javascript:reiniciarchecks();">AQUI</a>');
	document.getElementById('numero_sincronizado').value='';
	document.getElementById('letra_sincronizada').value='';
	$('#servicio_atendido').text($('#s_servicios_'+cod_servicio).text());
	}
 }	
}

function turno(){
	seleccionado=0;
	num_sincronizado='';
	cod_servicio='';
	param_todos=0;
	$('.checkservices').each(function(index){
	 id=$(this).attr('id');
	 codi=id.split('_');
	 elcod=codi[1];
	 if(document.getElementById('secuenciaservice_'+elcod).value==''){
	  param_todos=1;
	 }
	 
	 if(document.getElementById(''+id).checked==true){
	 seleccionado=1;
	 part         = id.split('_');
	 cod_servicio = part[1];
	 }
	});
	
	if(param_todos==1){
	alert('para iniciar el turnero debe sincronizar un valor para cada servicio');
	return;
	}
	
	if(seleccionado==0){
	alert('debe seleccionar un servicio para iniciar el turnero');
	return;
	}
	
$('#accion_turnero').val('1|'+cod_servicio);	
      var date = new Date();
      var timestamp = date.getTime();

var v_dato = getDataServer("tipoguardar_multiple.php","?tipoguardar=Crear_turno&modulo=<?php echo($_SESSION['MODULO']); ?>&turnojornada=<?php echo($jornada);?>&servicio_sincronizado="+cod_servicio+"&C_secuencia="+document.getElementById('secuencia_'+cod_servicio).value+"&numero_turno="+document.getElementById('secuenciaservice_'+cod_servicio).value+"&parametro_G="+document.getElementById('paramservice_'+cod_servicio).value+"&letra_sincronizada="+document.getElementById('letraturno_'+cod_servicio).value+"&time="+timestamp);
	if(v_dato!=''){
	//servicio_activo=$('#accion_turnero').val().split('|');
	//$('#accion_turnero').val('1|'+cod_servicio);
	var v_campos;
	var caracter='/';
	
    v_campos=v_dato.split(caracter);
	
	var valor1=v_campos[0]; <?php /* numero de turno turncoas*/?>
	var valor2=v_campos[1]; <?php /* parametro*/?>
	var valor3=v_campos[2]; <?php /* consecutivo TURNCONS*/?>
	var valor4=v_campos[3]; <?php /* la letra*/?>
	
	document.getElementById('num_atendidos').innerHTML               = valor4+''+valor1;
	document.getElementById('turno_actual').innerHTML                = valor4+''+valor1; 
	document.getElementById('letraturno_'+cod_servicio).value        = valor4;
	document.getElementById('secuenciaservice_'+cod_servicio).value  = valor1; 
    //document.form3.numero_parametrizado.value          =valor2;
    //document.form3.consecutivo_turno.value             =valor3;
	document.getElementById('consecutivoturno_'+cod_servicio).value  = valor3; 
	document.form3.numero_turno.value                  =valor1;
	
	  document.getElementById('li_turno').style.display='none';
	  
	  setTimeout(function() {
						 try{
							document.getElementById('li_inicio').style.display='block';
							document.getElementById('li_sig').style.display='block';
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 2000 );
	  
	} 
}

function siguiente(){
	seleccionado=0;
	num_sincronizado='';
	cod_servicio='';
	$('.checkservices').each(function(index){
	 id=$(this).attr('id');
	 if(document.getElementById(''+id).checked==true){
	 seleccionado=1;
	 part         = id.split('_');
	 cod_servicio = part[1];
	 }
	});
	$('#accion_turnero').val('1|'+cod_servicio);
  document.getElementById('li_inicio').style.display='none';
  document.getElementById('li_turno').style.display='none';
  document.getElementById('li_sig').style.display='none';	
	
      var date = new Date();
      var timestamp = date.getTime();
var v_dato = getDataServer("tipoguardar_multiple.php","?tipoguardar=Siguiente_turno&modulo=<?php echo($_SESSION['MODULO']); ?>&turnojornada=<?php echo($jornada);?>&servicio_sincronizado="+cod_servicio+"&secuencia_N="+document.getElementById('secuencia_'+cod_servicio).value+"&numero_turno_N="+document.form3.numero_turno.value+"&consecutivo_N="+document.getElementById('consecutivoturno_'+cod_servicio).value+"&parametro_N="+document.getElementById('paramservice_'+cod_servicio).value+"&letra_sincronizada="+document.getElementById('letraturno_'+cod_servicio).value+"&time="+timestamp);
	//$('#accion_turnero').val('1|'+cod_servicio);
	var v_campos;
	var caracter='/'
    v_campos=v_dato.split(caracter);
	
	var valor1=v_campos[0]; <?php /*numero de turno turncoas*/?>
	var valor2=v_campos[1]; <?php /*parametro*/?>
	var valor3=v_campos[2]; <?php /*consecutivo turcons*/?>
	var valor4=v_campos[3]; <?php /*letra del turno*/?>
	
	document.getElementById('num_atendidos').innerHTML = valor4+''+valor1;
	document.getElementById('turno_actual').innerHTML  = valor4+''+valor1;
	document.getElementById('letraturno_'+cod_servicio).value = valor4;
	document.getElementById('secuenciaservice_'+cod_servicio).value = valor1;
    //document.form3.numero_parametrizado.value          =valor2;
    //document.form3.consecutivo_turno.value             =valor3;
	document.getElementById('consecutivoturno_'+cod_servicio).value = valor3;
	document.form3.numero_turno.value                  =valor1;
	
		  setTimeout(function() {
						 try{
							  document.getElementById('li_inicio').style.display='block';
							  document.getElementById('li_turno').style.display='none';
							  document.getElementById('li_sig').style.display='block';	  
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 2000 );  
	
}

function inicio(){
	seleccionado=0;
	num_sincronizado='';
	cod_servicio='';
	$('.checkservices').each(function(index){
	 id=$(this).attr('id');
	 if(document.getElementById(''+id).checked==true){
	 seleccionado=1;
	 part         = id.split('_');
	 cod_servicio = part[1];
	 }
	});
	$('#accion_turnero').val('2|'+cod_servicio);
var date = new Date();
      var timestamp = date.getTime();

	var v_dato = getDataServer("tipoguardar_multiple.php","?tipoguardar=Inicio_Atencion&I_consecutivo="+document.getElementById('consecutivoturno_'+cod_servicio).value+"&I_parametro="+document.getElementById('paramservice_'+cod_servicio).value+"&letra_sincronizada="+document.getElementById('letraturno_'+cod_servicio).value+"&time="+timestamp);
	//alert(v_dato);
	if(v_dato!=''){
	$('#accion_turnero').val('2|'+cod_servicio);
	var v_campos;
	var caracter='/'
    v_campos=v_dato.split(caracter);
	var valor1=v_campos[0];
	var valor2=v_campos[1];
	var valor3=v_campos[2];
	document.getElementById('num_atendidos').innerHTML =valor1;
	//document.getElementById('turno_actual').innerHTML =valor1;
    //document.form3.numero_parametrizado.value          =valor2;
    //document.form3.consecutivo_turno.value             =valor3;
    document.getElementById('consecutivoturno_'+cod_servicio).value             =valor3;
	
	  
	  document.getElementById('li_inicio').style.display='none';
      document.getElementById('li_sig').style.display='none';	  
	  setTimeout(function() {
						 try{
							document.getElementById('li_fin').style.display='block';
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 2000 );	  
	}
	
	
	
}	

function fin(){
	seleccionado=0;
	num_sincronizado='';
	cod_servicio='';
	$('.checkservices').each(function(index){
	 id=$(this).attr('id');
	 if(document.getElementById(''+id).checked==true){
	 seleccionado=1;
	 part         = id.split('_');
	 cod_servicio = part[1];
	 }
	});
	$('#accion_turnero').val('0|'+cod_servicio);
var date = new Date();
      var timestamp = date.getTime();
 var v_dato = getDataServer("tipoguardar_multiple.php","?tipoguardar=Fin_Atencion&F_consecutivo="+document.getElementById('consecutivoturno_'+cod_servicio).value+"&F_parametro="+document.getElementById('paramservice_'+cod_servicio).value+"&letra_sincronizada="+document.getElementById('letraturno_'+cod_servicio).value+"&time="+timestamp);
 if(v_dato!=''){
 //$('#accion_turnero').val('0|'+cod_servicio);
 var v_campos;
	var caracter='/'
    v_campos=v_dato.split(caracter);
	var valor1=v_campos[0]; <?php /*numero de turno turncoas*/?>
	var valor2=v_campos[1]; <?php /*parametro*/?>
	var valor3=v_campos[2]; <?php /*secuencia uno o dos*/?>
	var valor4=v_campos[3]; <?php /*consecutivo TURNCONS*/?>
	var valor5=v_campos[4]; <?php /*letra del turno*/?>
	document.getElementById('num_atendidos').innerHTML =valor5+''+valor1;
	document.getElementById('turno_actual').innerHTML  =valor5+''+valor1;
	document.getElementById('letraturno_'+cod_servicio).value  =valor5;
	
	document.getElementById('secuenciaservice_'+cod_servicio).value = valor1;
    
    document.getElementById('secuencia_'+cod_servicio).value        = valor3;
	document.getElementById('consecutivoturno_'+cod_servicio).value             =valor4;
 
	  document.getElementById('li_fin').style.display='none';	  
	  document.getElementById('li_inicio').style.display='none';
	  
	  
	  setTimeout(function() {
						 try{
							document.getElementById('li_turno').style.display='block';
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 1200 );	  
 }
}

function salir(){
	/*var date = new Date();
		  var timestamp = date.getTime();
	var v_dato = getDataServer("tipoguardar.php","?tipoguardar=ComprobarSalirTurno&S_modulo=<?php echo($_SESSION['MODULO']); ?>&S_consecutivo="+document.form3.consecutivo_turno.value+"&S_parametro="+document.form3.numero_parametrizado.value+"&time="+timestamp);
	if(v_dato=='si'){
	 alert('tiene turno pendiente en curso');
	}
	*/
	
if(confirm('seguro que desea salir?')){
	var date = new Date();
		  var timestamp = date.getTime();
	var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Salir_Turno&S_modulo=<?php echo($_SESSION['MODULO']); ?>&S_consecutivo="+document.form3.consecutivo_turno.value+"&S_parametro="+document.form3.numero_parametrizado.value+"&time="+timestamp);
	 //alert(v_dato);
	 if(v_dato=='pendiente'){
	  alert('tiene un turno pendiente en curso');
	 }
	 if(v_dato =='salir'){
	   modulo    = document.getElementById('sesion_modulo').value;
	   servicio  = document.getElementById('sesion_servicio').value;
	   usuario   = document.getElementById('sesion_usuario').value;
	window.location.assign("logout.php?modulo="+modulo+"&servicio="+servicio+"&idusuario="+usuario)
	}
}

}

function contador(){
/*var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Contar_Turno&C_parametro="+document.form3.numero_parametrizado.value);
 if(v_dato!=''){
 alert('Turnos Atendidos: '+v_dato);
 
}*/
}

function mostrar(){
document.getElementById('contentmultiple').style.display='none';
document.getElementById('T_sincronizado').style.display='block';
document.getElementById('content').style.display='block';
document.getElementById('contentayuda').style.display='none';
document.getElementById('li_inicio').style.display='none';
document.getElementById('li_sig').style.display='none';
document.getElementById('li_fin').style.display='none';
}
function ocultar(){
document.getElementById('contentmultiple').style.display='none';
document.getElementById('T_sincronizado').style.display='none';
document.getElementById('content').style.display='block';
document.getElementById('contentayuda').style.display='none';
document.getElementById('li_inicio').style.display='none';
document.getElementById('li_sig').style.display='none';
document.getElementById('li_fin').style.display='none';
}

function ayuda(){
document.getElementById('contentmultiple').style.display='none';
      var date = new Date();
      var timestamp = date.getTime();
var v_dato = getDataServer("tipoguardar.php","?tipoguardar=ComprobarTurnoPendiente&timer="+timestamp);
	if(v_dato=='no'){
	  generarAyuda('1');
	  document.getElementById('content').style.display='none';
	  document.getElementById('contentayuda').style.display='block';
	 }
	if(v_dato=='si'){
      document.getElementById('content').style.display='block';
	  document.getElementById('contentayuda').style.display='none';
	 alert('tiene un turno pendiente, por favor de gestion antes de iniciar el modo apoyo');
    }	
}

function multiple(){
 document.getElementById('contentmultiple').style.display='block';
}

function Ayudar(idusu,modulo,servicio){
  if(confirm('seguro que desea realizar un turno de ayuda?')){
  document.getElementById('li_inicioAyuda').style.display='block'; 
  //document.getElementById('li_sigAyuda').style.display='block';
var date = new Date();
      var timestamp = date.getTime();  
  var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Crear_TurnoAyuda&user_ayudar="+idusu+"&modulo="+modulo+"&servicio="+servicio+"&time="+timestamp);
	  if(v_dato!=''){
         var v_campos;
			var caracter='/'
			v_campos        = v_dato.split(caracter);
			var turno       = v_campos[0];
			var parametro   = v_campos[1];
			var consecutivo = v_campos[2];
			document.getElementById('num_atendidosAyuda').innerHTML       = turno;
			document.getElementById('turno_ayuda').value       = turno;
			document.getElementById('parametro_ayuda').value   = parametro;
			document.getElementById('consecutivo_ayuda').value = consecutivo;
			
	  }
  }
}

function inicioAyuda(){
var date = new Date();
      var timestamp = date.getTime();
	var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Inicio_AtencionAyuda&turno_ayuda="+document.getElementById('turno_ayuda').value+"&parametro_ayuda="+document.getElementById('parametro_ayuda').value+"&consecutivo_ayuda="+document.getElementById('consecutivo_ayuda').value+"&time="+timestamp);

	if(v_dato!=''){
	var v_campos;
	var caracter='/'
    v_campos=v_dato.split(caracter);
	var valor1=v_campos[0];
	var valor2=v_campos[1];
	var valor3=v_campos[2];
	document.getElementById('num_atendidosAyuda').innerHTML = valor1;
	//document.getElementById('turno_actual').innerHTML =valor1;
    document.getElementById('parametro_ayuda').value        = valor2;
    document.getElementById('consecutivo_ayuda').value      = valor3;
	
	  
	  document.getElementById('li_inicioAyuda').style.display='none';
      //document.getElementById('li_sigAyuda').style.display='none';	  
	  setTimeout(function() {
						 try{
							document.getElementById('li_finAyuda').style.display='block';
							}catch(exc){
						  if(exc.description==null){
						  //alert("excepcion "+exc.message);
						  }else{
						   //alert("Excepcion: "+exc.description);
						  }
						}									  
					}, 2000 );
	}
}

function finAyuda(){
var date = new Date();
      var timestamp = date.getTime();
 var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Fin_AtencionAyuda&consecutivo_ayuda="+document.getElementById('consecutivo_ayuda').value+"&parametro_ayuda="+document.getElementById('parametro_ayuda').value+"&time="+timestamp);
 if(v_dato!=''){
 /*
 var v_campos;
	var caracter='/'
    v_campos=v_dato.split(caracter);
	var valor1=v_campos[0];
	var valor2=v_campos[1];
	var valor3=v_campos[2];
	var valor4=v_campos[3];
	document.getElementById('num_atendidosAyuda').innerHTML =valor1;
	document.getElementById('turno_actual').innerHTML =valor1;
	document.form3.numero_turno.value 				   =valor1;
    document.form3.numero_parametrizado.value          =valor2;
    document.form3.secuencia.value                     =valor3;
	document.form3.consecutivo_turno.value             =valor4;
	*/
 
	  document.getElementById('li_finAyuda').style.display='none';	  
	  document.getElementById('li_inicioAyuda').style.display='none';
	  /*document.getElementById('li_turnoAyuda').style.display='block';*/
 }
}

function ModoNormal(){
      var date = new Date();
      var timestamp = date.getTime();
var v_dato = getDataServer("tipoguardar.php","?tipoguardar=ComprobarTurnoPendienteAyuda&timer="+timestamp);
//alert(v_dato);
	if(v_dato=='no'){
	  //generarAyuda('1');
      document.getElementById('content').style.display='block';
      document.getElementById('contentayuda').style.display='none';
	 }
	if(v_dato=='si'){
      document.getElementById('content').style.display='none';
	  document.getElementById('contentayuda').style.display='block';
	 alert('tiene un turno pendiente en modo ayuda, por favor de gestion antes de iniciar el modo normal');
    }

}
function OcultarSincr(){
  document.getElementById('T_sincronizado').style.display="none";
}
</script>
</head>
<body>
<!-- wrapper starts -->
<div class="wrapper"> 
  <!-- Header Starts -->
  <div class="continer" style="overflow:hidden;">
    <div class="header">
      <div class="logo"><a href="#">Turnos Web</a></div>
      <div id="nav">
        <ul>
          <?php /*<li><a href="javascript:multiple();">MODO MULTIPLE</a></li>*/ ?>
          <?php /*<li><a href="javascript:ayuda();">MODO AYUDA</a></li> */ ?>
		 
          <li id="li_sincronizar" style="display:none;"><a href="javascript:mostrar();">SINCRONIZAR</a></li>
		  <?php if ($rol_usuario=='4'){ ?>
          <li><a href="panel_control.php">USUARIOS</a></li>
		  <?php } ?>
          <?php /*<li><a href="javascript:contador();">CONTADOR</a></li>*/?>
          <li class="pad_last"><a href="javascript:salir();">SALIR</a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
	</div>
    <!-- Header ends --> 
    <!-- Banner Starts -->

		  <div id="bienvenidatext" class="bienvenida">
	 Bienvenido: <b><?php echo($_SESSION["USU_AUT_NOMB"]); ?></b> Modulo: <b><?php echo($_SESSION["NOMBRE_MODULO"]);?></b> SERVICIO: <b>Multiple</b>
      </div>
	<div id="contentmultiple" class="contentmultiple" style="display:none;" >
	 <label>Agregar Servicio</label>
	 <select name="servicio_multiple" id="servicio_multiple">
	    <option value="">Seleccione...</option>
	 <?php
		if($totalRows_RsServicios >0){
		 do{
		 ?>
		 <option value="<?php echo($row_RsServicios['CODIGO']);?>"><?php echo($row_RsServicios['NOMBRE']);?></option>
		 <?php
		  }while($row_RsServicios = mysql_fetch_assoc($RsServicios));
		}

	 ?>
	 </select>
	</div>
	<div id="contentayuda" class="contentayuda" style="display:none;">
	  <div id="link_ayuda" class="ayuda"  >
	</div>
		    <div id="num_atendidosAyuda" class="num_atendidos">
	     T
	   </div>
	<div class="typs">	
			 <ul  style="margin-left:40%; float:left; margin-top:-250px">
			  <li id="li_turnoAyuda" style="display:none;"> 
			   <a href="javascript:turnoAyuda();">LLAMAR</a>
			   </li>
				 <li class="l3" id="li_inicioAyuda" style="display:none;">
				<a href="javascript:inicioAyuda();">INICIO</a> 
				</li>
				<li class="l2" id="li_sigAyuda"  style="display:none;"> 
			   <a href="javascript:siguienteAyuda();">SIGUIENTE</a>
			   </li>
			  <li class="l4" id="li_finAyuda" style="display:none;">
				<a href="javascript:finAyuda();">FIN</a> 
			</li>
			</ul>
			<input type="hidden" name="turno_ayuda" value="" id="turno_ayuda">
			<input type="hidden" name="parametro_ayuda" value="" id="parametro_ayuda">
			<input type="hidden" name="consecutivo_ayuda" value="" id="consecutivo_ayuda">
	</div>	
	</div>
	<div id='link_cargandoayuda'></div>
    <div class="content" id="content">
      <div class="typs">
	   <div id="info_turnos" class="info">
	   <p>
	   	<div style="margin-left:15px; width: 895px; overflow:hidden; background:white; margin-top:10px">
	<table border="0">
	<tr>
	<?php /* <td valign="top" width="170">
	<div class="div_titleserv">Servicios Seleccionados:</div>
	 </td>
	 */?>
	 <td width="380" style="padding-left:10px;">
	  <div style="color: #5C6473;
    font-family: Georgia,serif;
    font-size: 12px;
    line-height: 1.4;
    margin-top: 0;">
	 
	   <?php
	   //ArmarLi($_SESSION["ARR_SERVI"],$turnos);
	   ?>
	   <input type="hidden" name="servicio_activo" id="servicio_activo" value="">
	   <input type="hidden" name="parametro_activo" id="parametro_activo" value="">
	   <input type="hidden" name="accion_turnero" id="accion_turnero" value="0|0">
	   <table>
	    <tr style="background:#ccc;" align="center">
		 <td width="200"><b>Servicio</b></td>
		 <td width="105"><b>No Turno</b></td>
		 <td width="85"><b>Letra</b></td>
		 <td><b>Sincr.</b></td>
		</tr>
		<?php
 if(is_array($_SESSION["ARR_SERVI"])){
   for($i=0; $i<count($_SESSION["ARR_SERVI"]); $i++){
			$query_RsServicios="SELECT * FROM servicios where SERVID = '".$_SESSION["ARR_SERVI"][$i]."'";
			$RsServicios = mysql_query($query_RsServicios, $turnos) or die(mysql_error());
			$row_RsServicios = mysql_fetch_assoc($RsServicios);		
			$totalRows_RsServicios = mysql_num_rows($RsServicios);
			 if($totalRows_RsServicios>0){
			 ?>
			 <tr class="s_servicios" id="s_servicios_<?php echo($row_RsServicios['SERVID']);?>">
			  <td style="border-bottom:solid 1px #2F93A4;"><?php echo($row_RsServicios['SERVNOMB']);?></td>
			  <td style="border-bottom:solid 1px #2F93A4;">
			   <input type="hidden" name="paramservice_<?php echo($row_RsServicios['SERVID']);?>" id="paramservice_<?php echo($row_RsServicios['SERVID']);?>" value="" size="3">
			   <input type="hidden" name="paramservicevalor_<?php echo($row_RsServicios['SERVID']);?>" id="paramservicevalor_<?php echo($row_RsServicios['SERVID']);?>" value="" size="3">
			   <input type="text" class="inputsecuencia" name="secuenciaservice_<?php echo($row_RsServicios['SERVID']);?>" id="secuenciaservice_<?php echo($row_RsServicios['SERVID']);?>" value="" size="3" readonly>
			   <input type="hidden" name="secuencia_<?php echo($row_RsServicios['SERVID']);?>" id="secuencia_<?php echo($row_RsServicios['SERVID']);?>" value="" size="3">
			   <input type="hidden" name="consecutivoturno_<?php echo($row_RsServicios['SERVID']);?>" id="consecutivoturno_<?php echo($row_RsServicios['SERVID']);?>" value="" size="3">
			   <div style="float:right;">
			    <input class="checkservices" type="checkbox" value="<?php echo($row_RsServicios['SERVID']);?>" name="checkserv_<?php echo($row_RsServicios['SERVID']);?>" id="checkserv_<?php echo($row_RsServicios['SERVID']);?>">
				</div>
			   </td>
			   <td style="border-bottom:solid 1px #2F93A4;"><input type="text" align="center" class="letra_turno" name="letraturno_<?php echo($row_RsServicios['SERVID']);?>" id="letraturno_<?php echo($row_RsServicios['SERVID']);?>" value="" size="2" readonly></td>
			   <td style="border-bottom:solid 1px #2F93A4;">
				 <div  id="paramactivo_<?php echo($row_RsServicios['SERVID']);?>" style="width:100%; text-align:center;">&nbsp;</div>
				</td>
			 </tr>
			 <?php
			 }
    }
 }		
		?>
	   </table>
	   </div>
	  </td>
	  <td style="padding-left:30px; font-size:12px;" width="320"><span id="aviso_seleccionado" style="font-style:italic;"></span></td>
	 </tr>
	<table>
	</div>
	   </p>
	    <p>
		<table width="100%" border="0" class="info_text" id="table_info">
		<tr>
		<td width="175" class="labeltextdefault">Fecha de inicio de turno:</td>
		<td width="227">
		<span style="font-size:11px;">
		<?php 
         //echo($inicio_turno);
         //echo($row_RsFechaActual['FECHA']);
		 ?>
		 
		 Cupos de turno:
		 <?php
		 echo($cupo_turno);
		 ?>	
         </span>
		 </td>
 		 <td width="60">		 </td>
		 <td rowspan="3">
		 <form name="form2" id="form2" method="post" action="">
			 <table width="90%" border="0" id="T_sincronizado" style="display:none;">
			   <tr>
				 <td width="170" class="labeltextdefault">INICIAR TURNO CON:</td>
				 <td width="159">		 
				  <input type="text" class="inputtext" name="numero_sincronizado" id="numero_sincronizado" value="" size="15">
				</td>
				<td width="59"><a class="btn btn-xs btn-default" href="javascript:f_sincronizar();" class="sbttn">ENVIAR</a></td>
			 </tr>
			 <tr>
			  <td class="labeltextdefault">USA LETRA:</td>
			  <td>
			    <input class="inputtext" type="text" name="letra_sincronizada" id="letra_sincronizada" onblur="this.value = this.value.toUpperCase();" size="15">
			  </td>
			  <td><a class="btn btn-xs btn-default" id="ocultarsincr" class="sbttn" href="javascript:OcultarSincr()">OCULTAR</a></td>
			 </tr>
			</table>
		</form>
		</td>
		</tr>
		<tr>
		  <td class="labeltextdefault">Valor Sincronizado:</td>
		  <td><div id="dato_sincronizado"><?php //echo($turno_sincronizado);?></div></td>
		</tr>
		<tr>
		 <td class="labeltextdefault">Turno Actual</td>
		 <td><div id="turno_actual"></div></td>
		</tr>
		
		</table>		 
		</p>
		
	   </div>
	    <form name="form3" id="form3" method="post" action="">		
		  <input type="hidden" name="parametro_A" id="parametro_A" value="">
	      <input type="hidden" name="numero_parametrizado" id="numero_parametrizado" value="">	
		  <input type="hidden" name="consecutivo_turno" id="consecutivo_turno" value="">
		  <input type="hidden" name="secuencia" id="secuencia" value="">
		  <input type="hidden" name="numero_turno" id="numero_turno" value="">
		  <input type="hidden" name="sesion_modulo" id="sesion_modulo" value="<?php if(isset($_SESSION["MODULO"]) && $_SESSION["MODULO"]!=''){ echo($_SESSION["MODULO"]);}?>">
		  <input type="hidden" name="sesion_servicio" id="sesion_servicio" value="<?php if(isset($_SESSION["ID_SERVI"]) && $_SESSION["ID_SERVI"]!=''){ echo($_SESSION["ID_SERVI"]);}?>">
		  <input type="hidden" name="sesion_usuario" id="sesion_usuario" value="<?php if(isset($_SESSION["IDUSU"]) && $_SESSION["IDUSU"]!=''){ echo($_SESSION["IDUSU"]);}?>">
		</form>
	    <div id="servicio_atendido" class="servicio_atendido" style="min-height:50px; margin-left:15px; background:white; text-align:center; width:895px;">
	  
	   </div>
	   <div style="clear:both; background:white;width:895px; margin-left:15px" >&nbsp;
	   </div>
	    <div id="num_atendidos" class="num_atendidos" style="min-height:50px; margin-left:15px; background:white; text-align:center; width:895px;">
	     T
	   </div>
	   <div style="background:white; min-height: 10px; margin-left: 15px; width: 895px; overflow:hidden; padding-bottom:10px;">
        <ul style="margin-left:40%; " >
          <li id="li_turno" style="display:none;"> 
		   <a href="javascript:turno();">LLAMAR</a>
           </li>
             <li class="l3" id="li_inicio" style="display:none;">
            <a href="javascript:inicio();">INICIO</a> 
			</li>
			<li class="l2" id="li_sig"  style="display:none;"> 
		   <a href="javascript:siguiente();">SIGUIENTE</a>
           </li>
          <li class="l4" id="li_fin" style="display:none;">
            <a href="javascript:fin();">FIN</a> 
		</li>
        </ul>
	  </div>	
        <div class="clear"></div>
      </div>
      <div class="banner">
	    <?php /*<img src="images/banner_img.jpg" width="940" height="116" alt="img" /> 
		*/?>
		</div>
      <div id="usuariosenlinea" class="news">
	    <p align="center"><b>USUARIOS CONECTADOS AL SISTEMA</b></p>
		<table id="listausuarios">
		 <tr style="font-weight:bold;">
		  <td width="150">Persona</td>
		  <td>Modulo</td>
		 </tr>
		</table>
	  </div>
    </div>
    <!-- Banner End --> 
    <!-- FOOTER Starts -->
    <div class="footer">
      <div class="footer_continer">
        <div class="footer_top">
		<?php /*
          <div class="footer_nav">
            <ul>
              <li><a href="#">HOME</a></li>
              <li><a href="#">ABOUT</a></li>
              <li><a href="#">SERVICES</a></li>
              <li><a href="#">BLOG</a></li>
              <li class="pad_last no_bg"><a href="#">CONTACT</a></li>
            </ul>
            <div class="clear"></div>
          </div>
          <div class="social"><a href="#" class="tw">Twitter</a> <a href="#" class="fb">Facebook</a></div>
		  */?>
          <div class="clear"></div>
        </div>
      </div>
      <div class="copy">
        <p>Copyright © 2014 </a></p>
      </div>
    </div>
  </div>
  <!-- FOOTER END --> 
</div>
<!-- WARRPER END -->
</body>
</html>