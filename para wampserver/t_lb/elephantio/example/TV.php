<?php
//This product includes PHP software, freely available from <http://www.php.net/software/>

require_once('Connections/db.php');

mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");

$sonido="";
        $query_RsDatosBienvenida="DELETE FROM turnos where TURNIDUS = '0'";
		$RsDatosBienvenida = mysql_query($query_RsDatosBienvenida, $turnos) or die(mysql_error());

$query_RsllAMADOTV="SELECT      MODUID  CODIGO_MODULO,
								MODUNOMB MODULO,
								(select SERVCOLO
								  FROM servicios S
								 where S.SERVID = TURNSERV) COLOR,                                 
                                (select 	SERVNOMB
								  FROM servicios S
								 where S.SERVID = TURNSERV) SERVICIO,
								TURNCOAS TURNO,
								TURNCONS CONSECUTIVO
						FROM   turnos ,
        					   modulos,
							   estados
						WHERE ESTAID =3
						AND TURNMODU = MODUID
						AND TURNIDES = ESTAID
						ORDER BY MODUID
						";
						//echo($query_RsllAMADOTV);
	$RsllAMADOTV = mysql_query($query_RsllAMADOTV, $turnos) or die(mysql_error());
	$row_RsllAMADOTV = mysql_fetch_assoc($RsllAMADOTV);	
	$totalRows_RsllAMADOTV = mysql_num_rows($RsllAMADOTV);

if($totalRows_RsllAMADOTV > 0)
{$sonido='autoplay';}else
{$sonido='';}
?><!DOCTYPE html>
<html>
    <head>
        <title>PANTALLA TV</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="jquery/jquery1.10.2.min.js"></script>
		<script src="jquery/ion.sound.js"></script>
        <script src="socket/socket.io.min.js"></script>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />		
        <script>
            var socket;
            $(document).ready(function() {
			
						$.ionSound({
					sounds: [
						"llamadobueno2"
					],
					path: "sounds/",
					multiPlay: true,
					volume: "1.0"
				})
                socket = new io.connect('localhost', {
                    port: 8000
                });
            
                socket.on('connect', function() {
                    console.log('Client has connected to the server!');
                });

                socket.on('message', function(data) {
                    $('#chat').prepend('<div>' + data.usu + ': ' + data.msg + '</div>');
                });

                socket.on('PANTALLATV', function(data) {
                    //$('#chat').append('<div>' + data.usu + ': ' + data.msg + '</div>');
                    //$('#chat').append('<div>' + data + '</div>');
					v_campos=data.split('!');
							for(var i=0; i<v_campos.length-1; i++){
							   var datos=v_campos[i].split('/');
							   var codigo_modulo   = datos[0];
							   var nombre_modulo   = datos[1];
							   var turno           = datos[2];
							   var consecutivo     = datos[3];
							   var color           = datos[4]; //alert('2)'+color);
							   var nombre_servicio = datos[5]; 
							   
							   var nombre_div='div_turn_'+turno+'_'+codigo_modulo+'_'+consecutivo;
							   //alert(nombre_div);
							   var existe= document.getElementById(nombre_div);
							   //alert(existe);
							   //alert('hi');
							   if(existe==null){
							   //alert('there no enter');
									 //var turnosadd=$('div.contenedor_turno');
									 $('div.contenedor_turno').each(function(index){
									   var turnoexist=$(this).attr('id');
									   var campdiv=turnoexist.split('_');
									   var nombre_moduloE  = campdiv[1];
									   var turnoE          = campdiv[2];
									   var consecutivoE    = campdiv[4];
									   var codigo_moduloE  = campdiv[3];
									  // alert(codigo_modulo+' - '+codigo_moduloE);
									   if(codigo_modulo==codigo_moduloE){
									   $(this).remove();
									   }
										   
									 })
									 $.ionSound.play("llamadobueno2");
								$("#contenedor").prepend("<div class='contenedor_turno' style='background:"+color+"' id='div_turn_"+turno+"_"+codigo_modulo+"_"+consecutivo+"'><div class='turnoA' style='background:"+color+"'>"+turno+"</div><div class='moduloA' style='border-color:"+color+"'>"+nombre_modulo+"</div><div class='serviA' style='border-color:"+color+"'>"+nombre_servicio+"</div></div>");
								     document.getElementById('peticiones_ok').value=parseInt(document.getElementById('peticiones_ok').value)+1;
									 if(parseInt(document.getElementById('peticiones_ok').value)>0){
									 document.getElementById('peticiones_ok').value=parseInt(document.getElementById('peticiones_ok').value)+1;
									 }
									/*
									if(parseInt(document.getElementById('peticiones_ok').value)>7){
									//alert('se recargara la pagina');
									 document.form1.submit();
									}
                                   */									
								}

															
							}					
                });

                socket.on('disconnect', function() {
                    console.log('The client has disconnected!');
                    $('#chat').html('');
                });
            });


        </script>
<style type="text/css">
body{
/*background:url(fondogradiente/45x850.jpg) repeat-x;*/
}
h3,h4,h5,h6{
color:BLACK;
}
#contenedor{
/*border:solid #ff0000 1px;*/
width:100%;
min-width:1230px;
}
#div_title{
text-align:center;
}
#div_turnmod{

}
.contenedor_turno{
/*border:solid #000000 1px;*/
clear:both;
padding-top:2px;
overflow:hidden;
margin-top:3px;
display:table;
}
.turno{
width:300px;
/*border:solid #ff0000 1px;*/
float:left;
text-align:center;
}
.modulo{
margin-left:5px;
width:700px;
/*border:solid #ff0000 1px;*/
float:left;
text-align:center;
}

.turnoA, .moduloA, .serviA {
font-size:130px;
font-weight: bold;
}
.turnoA{
width:300px;
/*border:solid #ff0000 1px;*/
float:left;
text-align:center;
}

.moduloA{
font-size:65px;
margin-left:10px;
min-width:900px;
/*border:solid #ff0000 1px;*/
float:left;
text-align:center;
}

.serviA{
font-size:65px;
margin-left:10px;
min-width:900px;
/*border:solid #ff0000 1px;*/
float:left;
text-align:center;
}

.serviA{
/*border-top:2px none !important;
border-style:dashed;
*/
border:0 none !important;
}
.moduloA{
/*
border-bottom:2px none !important;
border-style:dashed;*/
border:0 none !important;
}
</style>
		
    </head>
    <body>
        <div style="width: 300px">
            <div id="chat" style="height: 100%"></div>
        </div>
<form name="form1" method="post" action="">
  <div id="div_title">
     <h4>HOSPITAL SAN FRANCISCO</h4>
  </div>
  <div id="div_turnmod" class="div_turnmod">
   <div id="turno" class="turno"><h3>TURNO</h3></div>
   <div id="modulo" class="modulo"><h3>CENTRAL DE CITA</h3></div>
  </div>
  <div id="contenedor">
  <?php
  	if ($totalRows_RsllAMADOTV > 0) { // Show if recordset not empty 
     do {
	    ?>
		  <div style="BACKGROUND:<?php echo($row_RsllAMADOTV['COLOR']);?>" class="contenedor_turno" id="div_turn_<?php echo($row_RsllAMADOTV['TURNO']);?>_<?php echo($row_RsllAMADOTV['CODIGO_MODULO']);?>_<?php echo($row_RsllAMADOTV['CONSECUTIVO']);?>">
		  <?php /*<div>*/?>
		   <div  class="turnoA"  style="BACKGROUND:<?php echo($row_RsllAMADOTV['COLOR']);?>" ><?php echo($row_RsllAMADOTV['TURNO']);?></div>
		   <div  class="moduloA" style="border-color:<?php echo($row_RsllAMADOTV['COLOR']);?>;"><?php echo($row_RsllAMADOTV['MODULO']);?></div>	
		   <div  class="serviA"  style="border-color:<?php echo($row_RsllAMADOTV['COLOR']);?>;" ><?php echo($row_RsllAMADOTV['SERVICIO']);?></div>
		  </div>	
		 <?php /*</div>*/?>
		<?php
		}while($row_RsllAMADOTV = mysql_fetch_assoc($RsllAMADOTV));
	  }
	 ?>
</div>
<input type="hidden" name="peticiones_ok" id="peticiones_ok" value="0"> 
</form>
		
    </body>
</html>