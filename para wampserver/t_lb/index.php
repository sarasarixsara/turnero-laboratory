<?php
//require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
	
mysql_select_db($database_turnos, $turnos);
@mysql_query("SET collation_connection = utf8_general_ci;");
mysql_query ("SET NAMES 'utf8'");
        
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

		$query_RsModulo="SELECT MODUID CODIGO,
		                        MODUNOMB NOMBRE								
						  FROM modulos 
						WHERE MODUTIPO = '2'
						";
		$RsModulo = mysql_query($query_RsModulo, $turnos) or die(mysql_error());
		$row_RsModulo = mysql_fetch_assoc($RsModulo);		
		$totalRows_RsModulo = mysql_num_rows($RsModulo);
		//echo(date('Y-m-d h'));
	
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
<script src="js/messages.js" type="text/javascript"></script>
	<script src="jquery/jquery-1.10.2.min.js"></script>
	<script src="socket/socket.io.min.js"></script>

<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/messages.css" rel="stylesheet" type="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- END: STYLESHEET -->
<!-- SET: SCRIPTS -->
<style type="text/css">
 .labellogin {
font-family: Verdana,Arial,Helvetica,sans-serif;
font-size: 15px;
color: #777;
}
 .inputlogin{
 height:32px;
 border:1px solid #ccc;
 border-radius:4px;
 font-size:15px;
 color:#555;
 margin:3px 12px;
 }
.inputlogin:focus{
background:#EFFFE0;
border-color: #66afe9;
box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(102, 175, 233, 0.6);
outline: 0 none;
}
.logo p{
color:#777;
font-size:22px;
font-weight:600;
}
.nodeoff{
z-index:99998;
background:#000000;
position:fixed;
width:100%;
height:100%;
top:0;
opacity: 0.8;
filter: alpha(opacity=80);
text-align:center;
}
.nodeoff-info {
    color: #A94445;
    font-size: 32px;
    left: 32%;
    position: absolute;
    top: 27%;
	border:solid 2px #E2B6B6;
	heigth:400px;
	background:#EBCCCC;
	border-radius:4px;
	padding:12px;
	z-index:99999;
	display:block;
	box-shadow: 0 8px 15px 0 #000;
}
.nodeoff-info p{
    color: ##A97573;
    font-size: 23px;
    left: 0;
    position: relative;
    top: 38%;
	font-weight:bold;
	text-align:center;
	
}
.nodeoff-info span a{
font-style:italic;
color:#ff0000;
}
</style>
<script type="text/javascript">
function validar(){
  usuario  = document.getElementById('usuario').value;
  password = document.getElementById('password').value;
  modulo   = document.getElementById('modulo').value;
   if(usuario == '')
  {
   inlineMsg('usuario','debe ingresar su nombre de usuario.',3);
		return false;
  }   
  if(password == '')
  {
   inlineMsg('password','debe ingresar su contraseña de ingreso.',3);
		return false;
  }  
  if(modulo == '')
  {
   inlineMsg('modulo','debe ingresar el modulo.',3);
		return false;
  }

document.formlogin.action="login.php";  
  
}

function Ingreso(){
document.formlogin.action="reset_user.php";  
document.formlogin.submit();
}
</script>
        <script>
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
		    var node_on='0';
            var socket;
            $(document).ready(function() {
			/*var v_dato = getDataServer("localhost:8000","?tipoGuardar=comprobarnode");
			alert(v_dato);
			*/
              
                socket = new io.connect('<?php echo($nodeserver);?>', {
                    port: <?php echo($nodeport);?>
                });
			           
                socket.on('connect', function() {
                    //console.log('Client has connected to the server!');
					     setTimeout(function() {
							 try{
								 node_on = '1';
								 }catch(exc){
								}
								}, 300 );
                });

                socket.on('disconnect', function() {
                    //
                });
				
					     setTimeout(function() {
							 try{
								 if(node_on == '0'){
								  divnodeoff='<div id="nodeoff" class="nodeoff"></div>';
	divnodemsg='<div class="nodeoff-info"><p>No hay conexion con el servidor en:<br><span><a target="_blank" href="http://<?php echo($nodeserver.":".$nodeport);?>"><?php echo($nodeserver.":".$nodeport);?></a></span><br> verifique e intente cargar <br>nuevamente la pagina</p><p><a href="" class="btn btn-danger btn-lg">Actualizar</a></p></div>';
								  $("#usuario").val('');
								  $("#password").val('');
								  $("#modulo").val('');
								  $('body').append(divnodeoff);
								  $('body').append(divnodemsg);
								 }
								 }catch(exc){
								}
								}, 2600 );				
				
            });
			


        </script>
</head>
<body>
<!-- wrapper starts -->
<div class="wrapper"> 
  <!-- Header Starts -->
  <div class="continer">
    <div class="header">
      <div class="logo"><p class="navbar-brand">Sistema de turnos</p></div>
      <div id="nav">
        <ul>
          <li><a href="#"></a></li>
          <li><a href="#"></a></li>
          <li><a href="#"></a></li>
          <li><a href="#"></a></li>
          <li class="pad_last"><a href="#"></a></li>
        </ul>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
    <!-- Header ends --> 
    <!-- Banner Starts -->
    <div class="content">
      <div class="typs">
	   <form name="formlogin" id="formlogin" action="" method="post">
          <table align="center">
		   <tr>
		     <td class="labellogin">Usuario&nbsp;&nbsp;&nbsp;</td>
		     <td><input class="inputlogin" type="text" name="usuario" id="usuario" value="" size="40" placeholder="Nombre de Usuario"></td>
		   </tr>
		   <tr>
			 <td class="labellogin">Clave</td>
			 <td><input class="inputlogin" type="password" name="password" id="password" value="" size="40" placeholder="Password"></td>
		   </tr>
		   <tr>
		    <td class="labellogin">Modulo</td>
			<td>
			 <select name="modulo" id="modulo" class="inputlogin">
			  <option value="">Seleccione...</option>
			<?php
			if($totalRows_RsModulo>0){
			  do{
			  ?>
			 <option value="<?php echo($row_RsModulo['CODIGO']);?>" ><?php echo($row_RsModulo['NOMBRE']);?></option>
			  <?php
			   }while($row_RsModulo = mysql_fetch_assoc($RsModulo));
			}
			?>
			</td>
		   </tr>
		   <tr>
		    <td colspan="2" align="center"><input class="btn btn-lg btn-default" type="submit" value="Ingresar" onclick="return validar();"></td>
		   </tr>
		   <?php /*
		   <tr>
		    <td colspan="2"><span style="color: #1F552E;
font-weight: bold;">Si no puedes acceder click <a class="linking" href="javascript: Ingreso();">aqui</a></span></td>
		   </tr>
		   */?>
		  </table>
		 </form>
        <div class="clear"></div>
      </div>
    </div>
    <!-- Banner End --> 
    <!-- FOOTER Starts -->
    <div class="footer">
      <div class="footer_continer">
        
      <div class="copy">
       <p>Copyright © 2014</a></p>
      </div>
    </div>
  </div>
  <!-- FOOTER END --> 
</div>
<!-- WARRPER END -->
</body>
</html>