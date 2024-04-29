<?php
require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
	session_start();
}

// mysql_select_db($database_turnos, $turnos);
// @mysql_query("SET collation_connection = utf8_general_ci;");
// mysql_query ("SET NAMES 'utf8'");
/*
$ARRAY_SERVICIOS[0]=1;
$ARRAY_SERVICIOS[1]=2;
$ARRAY_SERVICIOS[2]=3;
$ARRAY_SERVICIOS[3]=4;
  */

$limite_finturno = 99;
$limite_inicioturno = 00;
$ARRAY_LETRAS[0] = 'A';
$ARRAY_LETRAS[1] = 'B';
$ARRAY_LETRAS[2] = 'C';
$ARRAY_LETRAS[3] = 'D';
$ARRAY_LETRAS[4] = 'E';
$ARRAY_LETRAS[5] = 'F';
$ARRAY_LETRAS[6] = 'G';
$ARRAY_LETRAS[7] = 'H';
$ARRAY_LETRAS[8] = 'I';
$ARRAY_LETRAS[9] = 'J';
$ARRAY_LETRAS[10] = 'K';
$ARRAY_LETRAS[11] = 'L';
$ARRAY_LETRAS[12] = 'M';
$ARRAY_LETRAS[13] = 'N';
$ARRAY_LETRAS[14] = 'O';
$ARRAY_LETRAS[15] = 'P';
$ARRAY_LETRAS[16] = 'Q';
$ARRAY_LETRAS[17] = 'R';
$ARRAY_LETRAS[18] = 'S';
$ARRAY_LETRAS[19] = 'T';
$ARRAY_LETRAS[20] = 'U';
$ARRAY_LETRAS[21] = 'V';
$ARRAY_LETRAS[22] = 'W';
$ARRAY_LETRAS[23] = 'X';
$ARRAY_LETRAS[24] = 'Y';
$ARRAY_LETRAS[25] = 'Z';
/*

$TAMANO_ARR_LETRAS=count($ARRAY_LETRAS);
//echo($TAMANO_ARR_LETRAS-2);
$letra_turno='Z';
$no_enter=0;
$numero_turno=100;
if($numero_turno > 99){
 for($s=0; $s<$TAMANO_ARR_LETRAS; $s++){
						if($ARRAY_LETRAS[$s]==$letra_turno){
						  $indice_letra=$s;
						}
						

						
 }
 }
echo('la letra'.$indice_letra.'<br>');
if($indice_letra < ($TAMANO_ARR_LETRAS-1)){
 $letra_turno=$ARRAY_LETRAS[$indice_letra+1];
}

if($indice_letra==($TAMANO_ARR_LETRAS-1)){
$letra_turno=$ARRAY_LETRAS[0];
}

echo('la letra ahora es '.$letra_turno);
*/
$query_RsParams = "SELECT A.PARAVALOR NODESERVER,
                           (SELECT A2.PARAVALOR 					
						  FROM params_app A2 
						WHERE A2.PARACODI = '2') NODEPORT		
						  FROM params_app A 
						WHERE A.PARACODI = '1' 
						
						";
$RsParams = mysqli_query($turnos, $query_RsParams);
$row_RsParams = mysqli_fetch_assoc($RsParams);
$nodeserver = $row_RsParams['NODESERVER'];
$nodeport = $row_RsParams['NODEPORT'];
$query_RsServiciosMultiples = "SELECT M.MODUID MODULO,
 		                                    M.MODUNOMB MODULO_DES,
 											M.MODUUSUA USUARIO, 
											M.MODUSERV SERVICIOS,
											P.PERSNOMB PERSONA_DES
									FROM modulos M, 
									    personas P,
									    usuarios U
									where M.MODUUSUA = U.USUAID 
									  AND U.USUAIDPE = P.PERSCONS 
									  AND M.MODUMULT = 1";
$RsServiciosMultiples = mysqli_query($turnos, $query_RsServiciosMultiples);
$row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples);
$totalRows_RsServiciosMultiples = mysqli_num_rows($RsServiciosMultiples);

$contmult = '';
if ($totalRows_RsServiciosMultiples > 0) {
	$t = 0;
	do {
		$services = explode(",", $row_RsServiciosMultiples['SERVICIOS']);
		for ($z = 0; $z < count($services); $z++) {
			$contmult = $contmult . " ";
		}
	} while ($row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples));
}
/*	
for($i=0; $i<count($ARRAY_SERVICIOS); $i++){
echo($ARRAY_SERVICIOS[$i].'<br>');
}
*/
$rol_usuario = $_SESSION["ROL"];

$inicio_turno = '';
$cupo_turno = '';
$jornada = 1;
$turno_sincronizado = "";
/*if(isset($_SESSION["SINCRONIZADO"])){
$turno_sincronizado=$_SESSION["SINCRONIZADO"];
}

echo("este es el turno sincronizado ".$turno_sincronizado);
*/



$query_RsRangos = "SELECT SERVID    CODIGO,
		                        SERVRAFI  RANGO_FINAL,
								SERVRAIN  RANGO_INICIAL
						FROM `servicios` 
						WHERE 1
						AND SERVID = '" . $_SESSION["ID_SERVI"] . "'
						";
$RsRangos = mysqli_query($turnos, $query_RsRangos);
$row_RsRangos = mysqli_fetch_assoc($RsRangos);
$totalRows_RsRangos = mysqli_num_rows($RsRangos);


$query_RsServicios = "SELECT `SERVID` CODIGO,
						        `SERVNOMB`  NOMBRE,
								`SERVTUMA`,
								`SERVTUTA`,
								`SERVCOLO` 
						FROM `servicios` 
						WHERE 1
						AND SERVID != '" . $_SESSION["ID_SERVI"] . "'
						";
$RsServicios = mysqli_query($turnos, $query_RsServicios);
$row_RsServicios = mysqli_fetch_assoc($RsServicios);
$totalRows_RsServicios = mysqli_num_rows($RsServicios);


$query_RsDatosBienvenida = "SELECT M.MODUNOMB MODULO_DES,
		                           (
								    SELECT S.SERVNOMB
								      FROM servicios S
									 WHERE S.SERVID = '" . $_SESSION["ID_SERVI"] . "'
								   ) SERVICIO_DES
		                            FROM modulos M
								   where MODUID = '" . $_SESSION["MODULO"] . "'
								  ";
$RsDatosBienvenida = mysqli_query($turnos, $query_RsDatosBienvenida);
$row_RsDatosBienvenida = mysqli_fetch_assoc($RsDatosBienvenida);


$query_RsFechaActual = "SELECT SYSDATE() FECHA,
							  DATE_FORMAT(SYSDATE(), '%p') HORA,
							  (SELECT M.SERVTUMA
							    FROM SERVICIOS M
							   WHERE M.SERVID = '" . $_SESSION['ID_SERVI'] . "') MANANA,
							   (SELECT M2.SERVTUTA
							    FROM SERVICIOS M2
							   WHERE M2.SERVID = '" . $_SESSION['ID_SERVI'] . "') TARDE
							  
						";
$RsFechaActual = mysqli_query($turnos, $query_RsFechaActual);
$row_RsFechaActual = mysqli_fetch_assoc($RsFechaActual);
//echo('('.$row_RsFechaActual['FECHA'].')');
//$totalRows_RsFechaActual = mysqli_num_rows($RsFechaActual);
//echo($_SESSION["MODULO"]);
if ($row_RsFechaActual['HORA'] == 'AM') {
	$inicio_turno = '08:00:00 AM';
	$cupo_turno = $row_RsFechaActual['MANANA'];
	$jornada = 1;
}
if ($row_RsFechaActual['HORA'] == 'PM') {
	$inicio_turno = '2:00:00 PM';
	$cupo_turno = $row_RsFechaActual['TARDE'];
	$jornada = 2;
}




?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<script src="js/messages.js" type="text/javascript"></script>
	
	<link href="css/style.css" rel="stylesheet" type="text/css" />
	<link href="css/messages.css" rel="stylesheet" type="text/css" />
	<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&display=swap" rel="stylesheet">
	<!-- END: STYLESHEET -->
	<!-- SET: SCRIPTS -->
	<style type="text/css">
		body {

			height: 100vh;
			width: 100vw;
			overflow: hidden;
			font-family: "Sarabun", sans-serif;
			font-style: normal;
		}

		.info {
			border: 1px solid #eeeeee;
			border-radius: 15px;
			height: fit-content;
			min-width: fit-content;
			display: flex;
			flex-direction: column;
			padding: 15px;
			background-color:#f7f7f7;
			box-shadow: 0 8px 15px 0 #ccc;
		}

		.info_text {
			
			font-size: 20px;
		}

		.contentayuda {
			background: none repeat scroll 0 0 #FFFFFF;
			float: left;
			margin-left: auto;
			margin-right: auto;
			padding-bottom: 10px;
			padding-top: 20px;
			width: 978px;
		}

		.ayuda {
			background: none repeat scroll 0 0 #FFFFFF;
			float: left;
			padding-bottom: 10px;
			padding-top: 20px;
			width: 350px;
			min-height: 300px;
			overflow: hidden;
			margin-left: 20px;
			/*border: 1px solid #005A87;*/
		}

		.headerlabel {
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #003A57), color-stop(1, #00557F));
			background: -moz-linear-gradient(center top, #003A57 5%, #00557F 100%);
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
			/* FONT-FAMILY: Arial, Helvetica, Verdana; */
			BACKGROUND-COLOR: #666666;
			font-weight: bold;
			background-image: url(../com/img/FondoGris.gif);
		}

		.alt {
			background: #E1EEf4;
			color: #00557F;
			font-size: 11px;
			font-weight: normal;
		}

		.alt2 {
			background: #ffffff;
			color: #00557F;
			font-size: 11px;
			font-weight: normal;
		}

		#num_atendidosAyuda {
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			color: #000000;
			font-size: 85px;
		}

		#num_atendidos {
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: bold;
			color: #000000;
			font-size: 70px;
			padding: 50px;
			text-align: center;
		}

		.datagrid {
			/* font: normal 12px/150% Arial, Helvetica, sans-serif; */
			background: #fff;
			overflow: hidden;
			/*border: 2px solid #005A87;*/
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			border-collapse: collapse;
		}

		.datagrid td,
		.datagrid th {
			padding: 3px 2px;
		}

		.datagrid td ul {
			list-style: none outside none;
			margin: 0;
			padding: 0;
			text-align: right;
		}

		.datagrid td ul li {
			display: inline;
		}

		.datagrid li a {
			text-decoration: none;
			display: inline-block;
			padding: 2px 8px;
			margin: 1px;
			color: #FFFFFF;
			border: 1px solid #006699;
			-webkit-border-radius: 3px;
			-moz-border-radius: 3px;
			border-radius: 3px;
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F));
			background: -moz-linear-gradient(center top, #006699 5%, #00557F 100%);
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');
			background-color: #006699;
		}

		.datagrid ul.active,
		.datagrid ul a:hover {
			text-decoration: none;
			border-color: #00557F;
			color: #FFFFFF;
			background: none;
			background-color: #003A57;
		}

		.bienvenida {
			color: #000000;
			width: fit-content;
			font-size: 20px;
			font-weight: lighter;
			padding: 50px;
		}

		.contentmultiple {
			margin-top: 1px;
			margin-left: 27px;
			clear: both;
			background: #003A57;
			width: 930px;
			text-align: right;
		}
	</style>
	<script>
		ARRAY_LETRAS = new Array();
		<?php
		$letters = '';
		for ($k = 0; $k < count($ARRAY_LETRAS); $k++) {
			//$letters=$letters.$ARRAY_LETRAS[$k].',';
			?>
			ARRAY_LETRAS[<?php echo ($k); ?>] = '<?php echo ($ARRAY_LETRAS[$k]) ?>';
			<?php
		}
		?>
		//ARRAY_LETRAS=new Array("<?php echo ($letters); ?>");
		//alert(ARRAY_LETRAS[3]);
		//alert(ARRAY_LETRAS.length);
		var socket;
		$(document).ready(function () {
			manageSessions.unset("logueados");
			socket = new io.connect('<?php echo ($nodeserver); ?>', {
				port: <?php echo ($nodeport); ?>
			});
			manageSessions.set("logueados", '<?php echo ($_SESSION["IDUSU"]); ?>|<?php echo ($_SESSION["USU_AUT_NOMB"]); ?>|<?php echo ($_SESSION["MODULO"]); ?>|<?php echo ($_SESSION["NOMBRE_MODULO"]); ?>|<?php echo ($_SESSION["ID_SERVI"]); ?>|<?php echo ($_SESSION["SALA"]); ?>|0');
			socket.emit("UsuariosLogueados", manageSessions.get("logueados"));
			socket.on('disconnect', function () {
				console.log('sin conexion');
			});
			//si el usuario está en uso lanzamos el evento userInUse y mostramos el mensaje
			socket.on("userInUse", function () {
				//alert('el usuario que intenta acceder ya esta en uso');
				manageSessions.unset("logueados");
				alert('este usuario ya se encuentra registrado en otra ventana sera remitido a la pagina de login');
				location.href = "logout.php";
				return;
			});


			socket.on("MostrarUsuarios", function (usersOnline) {
				tablatitulos = "<tr><td width='150'><b>Persona</b></td><td><b>Modulo</b></td></tr>";
				//limpiamos el sidebar donde almacenamos usuarios
				$("#listausuarios").html("");
				$("#listausuarios").append(tablatitulos);
				//si hay usuarios conectados, para evitar errores
				if (!isEmptyObject(usersOnline)) {
					//recorremos el objeto y los mostramos en el sidebar, los datos
					//están almacenados con {clave : valor}
					$.each(usersOnline, function (key, val) {
						data = val.split('|');
						nombre = data[1];
						modulo_nombre = data[3];
						sala = data[4];
						if (sala == 2) {
							datostrtd = "<tr><td>" + nombre + "</td><td>" + modulo_nombre + "</td></tr>";
							$("#listausuarios").append(datostrtd);
						}
					})
				}
			});

			$('#nav ul>li').click(function (e) {
				id = $(this).attr('id');

				$('#nav ul>li').each(function (index) {
					idb = $(this).attr('id');
					if ($('#' + idb).removeClass("active")) {
					}
				});
				$("#" + id).addClass("active");
			});

		});

		//objeto para el manejo de sesiones
		var manageSessions = {
			//obtenemos una sesión //getter
			get: function (key) {
				return sessionStorage.getItem(key);
			},
			//creamos una sesión //setter
			set: function (key, val) {
				return sessionStorage.setItem(key, val);
			},
			//limpiamos una sesión
			unset: function (key) {
				return sessionStorage.removeItem(key);
			}
		};

		//función que comprueba si un objeto está vacio, devuelve un boolean
		function isEmptyObject(obj) {
			var name;
			for (name in obj) {
				return false;
			}
			return true;
		}
		window.onload = function () { mantenerSesion(); }

		function mantenerSesion() {
			setTimeout(function () {
				try {
					var date = new Date();
					var timestamp = date.getTime();

					var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=mantenerSesion");
					if (v_dato == 'listo') {
						mantenerSesion2();
					}
				} catch (exc) {
					if (exc.description == null) {
						//alert("excepcion "+exc.message);
					} else {
						//alert("Excepcion: "+exc.description);
					}
				}
			}, 600000);
		}
		function mantenerSesion2() {
			setTimeout(function () {
				try {
					var date = new Date();
					var timestamp = date.getTime();

					var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=mantenerSesion");
					if (v_dato == 'listo') {
						mantenerSesion();
					}
				} catch (exc) {
					if (exc.description == null) {
						//alert("excepcion "+exc.message);
					} else {
						//alert("Excepcion: "+exc.description);
					}
				}
			}, 600000);
		}
	</script>
	<script type="text/javascript">
		document.onkeydown = function (e) {
			if (e)
				document.onkeypress = function () { return true; }

			var evt = e ? e : event;
			if (evt.keyCode == 116) {
				if (e)
					document.onkeypess = function () { return false; }
				else {
					evt.keyCode = 0;
					evt.returnValue = false;
				}
			}
		}


		// function getDataServer(url, vars){
		// 	 var xml = null;
		// 	 try{
		// 		 xml = new ActiveXObject("Microsoft.XMLHTTP");
		// 	 }catch(expeption){
		// 		 xml = new XMLHttpRequest();
		// 	 }
		// 	 xml.open("GET",url + vars, false);
		// 	 xml.send(null); //hay error aca 
		// 	 if(xml.status == 404) alert("Url no valida");
		// 	 return xml.responseText;
		// }
		function getDataServer(url, vars) {
			var xml = null;
			try {
				xml = new XMLHttpRequest();
			} catch (ex) {
				try {
					xml = new ActiveXObject("Microsoft.XMLHTTP");
				} catch (ex) {
					alert("Tu navegador no soporta peticiones AJAX.");
					return null;
				}
			}
			xml.open("GET", url + vars, false);
			xml.send(null); // Se ejecuta después de xml.open()
			if (xml.status == 404) alert("URL no válida");
			return xml.responseText;
		}


		function getXMLHttpRequestAyuda() {
			var aVersions = ["MSXML2.XMLHttp.5.0", "MSXML2.XMLHttp.4.0", "MSXML2.XMLHttp.3.0", "MSXML2.XMLHttp", "Microsoft.XMLHttp"];
			if (window.XMLHttpRequest) {
				// para IE7, Mozilla, Safari, etc: que usen el objeto nativo
				return new XMLHttpRequest();
			}
			else if (window.ActiveXObject) {
				// de lo contrario utilizar el control ActiveX para IE5.x y IE6.x
				for (var i = 0; i < aVersions.length; i++) {
					try {
						var oXmlHttp = new ActiveXObject(aVersions);
						return oXmlHttp;
					}
					catch (error) {
						// no necesitamos hacer nada especial
					}
				}
			}
		}



		function explodeAyuda(nombre_div, pagina, tipoexport) {
			var aleatorio = Math.random();
			var cont = document.getElementById(nombre_div); // aqui esta la var nombre_div
			var cont_cargue = document.getElementById('link_cargandoayuda');
			ajax = getXMLHttpRequestAyuda();

			ajax.open('GET', pagina + "?tipoguardar=ModeAyuda&modulo=<?php echo ($_SESSION["MODULO"]); ?>&sala=<?php echo ($_SESSION["SALA"]); ?>"
				+ "&tipoexport=" + tipoexport
				+ "&nocache=" + aleatorio
				, true); // aqui esta la var "pagina"

			ajax.onreadystatechange = function () {

				if (ajax.readyState == 4) {
					if (ajax.status == 200) {
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

		function generarAyuda(exp) {
			//explodeAusentismo('link_beneficiarios','link_beneficiarios.php');
			explodeAyuda('link_ayuda', 'link_ayuda.php', exp);

		}

		function f_sincronizar() {
			numero_sincronizado = document.getElementById('numero_sincronizado').value;
			if (numero_sincronizado == '') {
				inlineMsg('numero_sincronizado', 'debe ingresar el numero del turno.', 3);
				return;
			}
			if (isNaN(document.getElementById('numero_sincronizado').value)) {
				document.getElementById('numero_sincronizado').value = '';
				inlineMsg('numero_sincronizado', 'debe ingresar un valor numerico.', 3);
				return;
			}

			var date = new Date();
			var timestamp = date.getTime();

			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Sincronizar_Turno&modulo=<?php echo ($_SESSION['MODULO']); ?>&turnojornada=<?php echo ($jornada); ?>&numero_sincronizado=" + document.getElementById('numero_sincronizado').value + "&letra_sincronizada=" + document.getElementById('letra_sincronizada').value + "&time=" + timestamp);
			//alert(v_dato);
			if (v_dato != '') {
				var v_campos;
				var caracter = '/';

				v_campos = v_dato.split(caracter);

				var valor1 = v_campos[0];

				document.form3.parametro_A.value = valor1;
				document.form3.secuencia.value = 'uno';
				document.getElementById('li_turno').style.display = 'block';
				document.getElementById('T_sincronizado').style.display = 'none';
				document.getElementById('dato_sincronizado').innerHTML = document.getElementById('letra_sincronizada').value + '' + document.getElementById('numero_sincronizado').value;
			}
		}

		function turno() {
			var date = new Date();
			var timestamp = date.getTime();

			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Siguiente_turno&modulo=<?php echo ($_SESSION['MODULO']); ?>&turnojornada=<?php echo ($jornada); ?>&secuencia_N=" + document.form3.secuencia.value + "&numero_turno_N=" + document.form3.numero_turno.value + "&consecutivo_N=" + document.form3.consecutivo_turno.value + "&parametro_N=" + document.form3.parametro_A.value + "&letra_sincronizada=" + document.getElementById('letra_sincronizada').value + "&time=" + timestamp);
			if (v_dato != '') {
				var v_campos;
				var caracter = '/'
				v_campos = v_dato.split(caracter);

				var valor1 = v_campos[0]; <?php /* numero de turno turncoas*/ ?>
				var valor2 = v_campos[1]; <?php /* parametro*/ ?>
				var valor3 = v_campos[2]; <?php /* consecutivo TURNCONS*/ ?>
				var valorLetra = v_campos[3]; <?php /* la letra*/ ?>
				var elementos = valorLetra.split("<br");
				var valor4 = elementos[0];
			

				document.getElementById('num_atendidos').innerHTML = valor4 + '' + valor1;
				document.getElementById('turno_actual').innerHTML = valor4 + '' + valor1;
				document.getElementById('letra_sincronizada').value = valor4;
				document.form3.numero_parametrizado.value = valor2;
				document.form3.consecutivo_turno.value = valor3;
				document.form3.numero_turno.value = valor1;

				document.getElementById('li_turno').style.display = 'none';

				setTimeout(function () {
					try {
						document.getElementById('li_inicio').style.display = 'block';
						document.getElementById('li_sig').style.display = 'block';

					} catch (exc) {
						if (exc.description == null) {
							//alert("excepcion "+exc.message);
						} else {
							//alert("Excepcion: "+exc.description);
						}
					}
				}, 2000);

			}
		}

		function siguiente() {
			document.getElementById('li_inicio').style.display = 'none';
			document.getElementById('li_turno').style.display = 'none';
			document.getElementById('li_sig').style.display = 'none';
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Siguiente_turno&modulo=<?php echo ($_SESSION['MODULO']); ?>&turnojornada=<?php echo ($jornada); ?>&secuencia_N=" + document.form3.secuencia.value + "&numero_turno_N=" + document.form3.numero_turno.value + "&consecutivo_N=" + document.form3.consecutivo_turno.value + "&parametro_N=" + document.form3.parametro_A.value + "&letra_sincronizada=" + document.getElementById('letra_sincronizada').value + "&time=" + timestamp);

			var v_campos;
			var caracter = '/'
			v_campos = v_dato.split(caracter);



			var valor1 = v_campos[0]; <?php /*numero de turno turncoas*/ ?>
			var valor2 = v_campos[1]; <?php /*parametro*/ ?>
			var valor3 = v_campos[2]; <?php /*consecutivo turcons*/ ?>
			var valorLetra = v_campos[3]; <?php /*letra del turno*/ ?>
			let elementos = valorLetra.split("<br");
			let valor4 = elementos[0];


			document.getElementById('num_atendidos').innerHTML = valor4 + '' + valor1;
			document.getElementById('turno_actual').innerHTML = valor4 + '' + valor1;

			document.getElementById('letra_sincronizada').value = valor4;
			document.form3.numero_parametrizado.value = valor2;
			document.form3.consecutivo_turno.value = valor3;
			document.form3.numero_turno.value = valor1;
			setTimeout(function () {
				try {
					document.getElementById('li_inicio').style.display = 'block';
					document.getElementById('li_turno').style.display = 'none';
					document.getElementById('li_sig').style.display = 'block';
				} catch (exc) {
					if (exc.description == null) {
						//alert("excepcion "+exc.message);
					} else {
						//alert("Excepcion: "+exc.description);
					}
				}
			}, 2000);
		}

		function inicio() {
			var date = new Date();
			var timestamp = date.getTime();

			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Siguiente_turno&modulo=<?php echo ($_SESSION['MODULO']); ?>&turnojornada=<?php echo ($jornada); ?>&secuencia_N=" + document.form3.secuencia.value + "&numero_turno_N=" + document.form3.numero_turno.value + "&consecutivo_N=" + document.form3.consecutivo_turno.value + "&parametro_N=" + document.form3.parametro_A.value + "&letra_sincronizada=" + document.getElementById('letra_sincronizada').value + "&time=" + timestamp);
			console.log(v_dato);
			if (v_dato != '') {

				var v_campos;
				var caracter = '/'

				v_campos = v_dato.split(caracter);
				var valor1 = v_campos[0];
				var valor2 = v_campos[1];
				var valor3 = v_campos[2];
				//  var valor4 = v_campos[3];
				var valorLetra = v_campos[3]; <?php /*letra del turno*/ ?>
				let elementos = valorLetra.split("<br");
				let valor4 = elementos[0];
				console.log(valor1, valor2, valor3, valor4);


				document.getElementById('num_atendidos').innerHTML = valor4 + '' + valor1;
				document.getElementById('turno_actual').innerHTML = valor4 + '' + valor1;
				// document.form3.numero_parametrizado.value = valor2;
				// document.form3.consecutivo_turno.value = valor3;
				document.form3.numero_parametrizado.value = valor2;
				document.form3.consecutivo_turno.value = valor3;
				document.form3.numero_turno.value = valor1;

				document.getElementById('li_inicio').style.display = 'none';
				document.getElementById('li_sig').style.display = 'none';
				setTimeout(function () {
					try {
						document.getElementById('li_fin').style.display = 'block';

					} catch (exc) {
						if (exc.description == null) {
							//alert("excepcion "+exc.message);
						} else {
							//alert("Excepcion: "+exc.description);
						}
					}
				}, 2000);
			}



		}

		function fin() {
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Fin_Atencion&F_consecutivo=" + document.form3.consecutivo_turno.value + "&F_parametro=" + document.form3.numero_parametrizado.value + "&letra_sincronizada=" + document.getElementById('letra_sincronizada').value + "&time=" + timestamp);
			if (v_dato != '') {

				var v_campos;
				var caracter = '/'
				v_campos = v_dato.split(caracter);
				var valor1 = v_campos[0]; <?php /*numero de turno turncoas*/ ?>
				var valor2 = v_campos[1]; <?php /*parametro*/ ?>
				var valor3 = v_campos[2]; <?php /*secuencia uno o dos*/ ?>
				var valor4 = v_campos[3]; <?php /*consecutivo TURNCONS*/ ?>
				var valor5 = v_campos[4]; <?php /*letra del turno*/ ?>
				document.getElementById('num_atendidos').innerHTML = valor5 + '' + valor1;
				document.getElementById('turno_actual').innerHTML = valor5 + '' + valor1;
				document.getElementById('letra_sincronizada').value = valor5;
				document.form3.numero_turno.value = valor1;
				document.form3.numero_parametrizado.value = valor2;
				document.form3.secuencia.value = valor3;
				document.form3.consecutivo_turno.value = valor4;

				document.getElementById('li_fin').style.display = 'none';
				document.getElementById('li_inicio').style.display = 'none';


				setTimeout(function () {
					try {
						document.getElementById('li_turno').style.display = 'block';
					} catch (exc) {
						if (exc.description == null) {
							//alert("excepcion "+exc.message);
						} else {
							//alert("Excepcion: "+exc.description);
						}
					}
				}, 1200);
			}
		}

		function salir() {
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=ComprobarSalirTurno&S_modulo=<?php echo ($_SESSION['MODULO']); ?>& S_consecutivo=" + document.form3.consecutivo_turno.value + " & S_parametro=" + document.form3.numero_parametrizado.value + " & time=" + timestamp);
			if (v_dato == 'si') {
				alert('tiene turno pendiente en curso');
			}


			if (confirm('seguro que desea salir?')) {
				var date = new Date();
				var timestamp = date.getTime();
				var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Salir_Turno&S_modulo=<?php echo ($_SESSION['MODULO']); ?>&S_consecutivo=" + document.form3.consecutivo_turno.value + "&S_parametro=" + document.form3.numero_parametrizado.value + "&time=" + timestamp);
				//alert(v_dato);
				if (v_dato == 'pendiente') {
					alert('tiene un turno pendiente en curso');
				}
				if (v_dato == 'salir') {
					modulo = document.getElementById('sesion_modulo').value;
					servicio = document.getElementById('sesion_servicio').value;
					usuario = document.getElementById('sesion_usuario').value;
					window.location.assign("logout.php?modulo=" + modulo + "&servicio=" + servicio + "&idusuario=" + usuario)
				}
			}

		}

		function contador() {
			/*var v_dato = getDataServer("tipoguardar.php","?tipoguardar=Contar_Turno&C_parametro="+document.form3.numero_parametrizado.value);
			 if(v_dato!=''){
			 alert('Turnos Atendidos: '+v_dato);
			 
			}*/
		}

		function mostrar() {
			if (document.getElementById('consecutivo_turno').value != '') {
				if (confirm('Seguro que desea sincronizar un nuevo turno?')) {
					document.getElementById('contentmultiple').style.display = 'none';
					document.getElementById('T_sincronizado').style.display = 'block';
					document.getElementById('content').style.display = 'block';
					document.getElementById('contentayuda').style.display = 'none';
					document.getElementById('li_inicio').style.display = 'none';
					document.getElementById('li_sig').style.display = 'none';
					document.getElementById('li_fin').style.display = 'none';
				}
			} else {
				document.getElementById('contentmultiple').style.display = 'none';
				document.getElementById('T_sincronizado').style.display = 'block';
				document.getElementById('content').style.display = 'flex';
				document.getElementById('contentayuda').style.display = 'none';
				document.getElementById('li_inicio').style.display = 'none';
				document.getElementById('li_sig').style.display = 'none';
				document.getElementById('li_fin').style.display = 'none';
			}
		}

		function ayuda() {
			document.getElementById('contentmultiple').style.display = 'none';
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=ComprobarTurnoPendiente&timer=" + timestamp);
			if (v_dato == 'no') {
				generarAyuda('1');
				document.getElementById('content').style.display = 'none';
				document.getElementById('contentayuda').style.display = 'block';
			}
			if (v_dato == 'si') {
				document.getElementById('content').style.display = 'block';
				document.getElementById('contentayuda').style.display = 'none';
				hideClassActiveMenu();
				alert('tiene un turno pendiente, por favor de gestion antes de iniciar el modo apoyo');
			}
		}

		function multiple() {
			document.getElementById('contentmultiple').style.display = 'block';
		}

		function Ayudar(idusu, modulo, servicio) {
			if (confirm('seguro que desea realizar un turno de ayuda?')) {
				document.getElementById('li_inicioAyuda').style.display = 'block';
				//document.getElementById('li_sigAyuda').style.display='block';
				var date = new Date();
				var timestamp = date.getTime();
				var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Crear_TurnoAyuda&user_ayudar=" + idusu + "&modulo=" + modulo + "&servicio=" + servicio + "&time=" + timestamp);
				if (v_dato != '') {
					var v_campos;
					var caracter = '/'
					v_campos = v_dato.split(caracter);
					var turno = v_campos[0];
					var parametro = v_campos[1];
					var consecutivo = v_campos[2];
					var letra = v_campos[3];
					document.getElementById('num_atendidosAyuda').innerHTML = letra + '' + turno;
					document.getElementById('turno_ayuda').value = turno;
					document.getElementById('parametro_ayuda').value = parametro;
					document.getElementById('consecutivo_ayuda').value = consecutivo;

				}
			}
		}

		function inicioAyuda() {
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Inicio_AtencionAyuda&turno_ayuda=" + document.getElementById('turno_ayuda').value + "&parametro_ayuda=" + document.getElementById('parametro_ayuda').value + "&consecutivo_ayuda=" + document.getElementById('consecutivo_ayuda').value + "&time=" + timestamp);

			if (v_dato != '') {
				var v_campos;
				var caracter = '/'
				v_campos = v_dato.split(caracter);
				var valor1 = v_campos[0];
				var valor2 = v_campos[1];
				var valor3 = v_campos[2];
				document.getElementById('num_atendidosAyuda').innerHTML = valor1;

				document.getElementById('parametro_ayuda').value = valor2;
				document.getElementById('consecutivo_ayuda').value = valor3;


				document.getElementById('li_inicioAyuda').style.display = 'none';
				//document.getElementById('li_sigAyuda').style.display='none';	  
				setTimeout(function () {
					try {
						document.getElementById('li_finAyuda').style.display = 'block';
					} catch (exc) {
						if (exc.description == null) {
							//alert("excepcion "+exc.message);
						} else {
							//alert("Excepcion: "+exc.description);
						}
					}
				}, 2000);
			}
		}

		function finAyuda() {
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=Fin_AtencionAyuda&consecutivo_ayuda=" + document.getElementById('consecutivo_ayuda').value + "&parametro_ayuda=" + document.getElementById('parametro_ayuda').value + "&time=" + timestamp);
			if (v_dato != '') {
				generarAyuda(); <?php /*actualizar la tabla contenedor de ayuda */ ?>


				document.getElementById('li_finAyuda').style.display = 'none';
				document.getElementById('li_inicioAyuda').style.display = 'none';
				/*document.getElementById('li_turnoAyuda').style.display='block';*/
			}
		}

		function ModoNormal() {
			var date = new Date();
			var timestamp = date.getTime();
			var v_dato = getDataServer("tipoguardar.php", "?tipoguardar=ComprobarTurnoPendienteAyuda&timer=" + timestamp);
			//alert(v_dato);
			if (v_dato == 'no') {
				//generarAyuda('1');
				document.getElementById('content').style.display = 'block';
				document.getElementById('contentayuda').style.display = 'none';
			}
			if (v_dato == 'si') {
				document.getElementById('content').style.display = 'none';
				document.getElementById('contentayuda').style.display = 'block';
				alert('tiene un turno pendiente en modo ayuda, por favor de gestion antes de iniciar el modo normal');
			}

		}

		function OcultarSincr() {
			document.getElementById('T_sincronizado').style.display = "none";
			hideClassActiveMenu();
		}
		function hideClassActiveMenu() {
			$('#nav ul>li').each(function (index) {
				idb = $(this).attr('id');
				if ($('#' + idb).removeClass("active")) {
				}
			});
		}
		function acceptNum(event) {
			var key;
			if (window.event) {
				key = event.keyCode;
			}
			else if (event.which) {
				key = event.which;
			}
			//return (key == 45 || key == 13 || key == 8 || key == 9 || key == 189 || (key >= 48 && key <= 58) )
			//var nav4 = window.Event ? true : false;
			//var key = nav4 ? evt.which : evt.keyCode;
			return (key <= 13 || (key >= 48 && key <= 57));
		}
	</script>
</head>

<body>
	<div style="width:100%; height: 100%;display:flex;flex-direction:column; justify-content:center;">			
		<div class="header">
				<div class="logo">
					<p>Sistema de turnos</p>
				</div>
				<div id="nav">
					<ul class="nav nav-pills">
						 <!-- <li><a href="javascript:multiple();">MODO MULTIPLE</a></li>  -->
						<!-- <li id="limodoayuda"><a href="javascript:ayuda();">Modo Ayuda</a></li> -->

						<li id="lisincronizar"><a href="javascript:mostrar();">Sincronizar</a></li>
						<?php if ($rol_usuario == '4') { ?>
							<li id="liusuarios"><a href="panel_control.php">Usuarios</a></li>
						<?php } ?>
						<?php /*<li><a href="javascript:contador();">CONTADOR</a></li>*/ ?>
						<li class="pad_last"><a href="javascript:salir();">Salir</a></li>
					</ul>
				</div>
		</div>

			<!-- comentado porque no se esta usando -->
		<div id="contentmultiple" class="contentmultiple" style="display:none;" >
			<label>Agregar Servicio</label>
			<select name="servicio_multiple" id="servicio_multiple">
				<option value="">Seleccione...</option>
				<?php
				if ($totalRows_RsServicios > 0) {
					do {
						?>
						<option value="<?php echo ($row_RsServicios['CODIGO']); ?>">
							<?php echo ($row_RsServicios['NOMBRE']); ?>
						</option>
						<?php
					} while ($row_RsServicios = mysqli_fetch_assoc($RsServicios));
				}

				?>
			</select>
		</div> 
		<div id="contentayuda" class="contentayuda" style="display:none;">
			<div id="link_ayuda" class="ayuda">
			</div>
			<div id="num_atendidosAyuda" class="num_atendidos">

				<!-- <div id="turno_actual"></div> -->
			</div>
			<div class="typs">
				<ul  class="flex ">
					<li id="li_turnoAyuda" style="display:none;">
						<a href="javascript:turnoAyuda();" class="text-3xl">LLAMAR</a>
					</li>
					<li class="l3" id="li_inicioAyuda" style="display:none;">
						<a href="javascript:inicioAyuda();">INICIO</a>

					</li>
					<li class="l2" id="li_sigAyuda" style="display:none;">
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
			<div id="bienvenidatext" class="bienvenida"><b>
					Bienvenido:
					<?php echo ($_SESSION["USU_AUT_NOMB"]); ?>.
					<?php echo ($row_RsDatosBienvenida['MODULO_DES']); ?> Servicio:
					<?php echo ($row_RsDatosBienvenida['SERVICIO_DES']); ?>
				</b>
			</div>
					<form name="form3" id="form3" method="post" action="">
						<input type="hidden" name="parametro_A" id="parametro_A" value="">
						<input type="hidden" name="numero_parametrizado" id="numero_parametrizado" value="">
						<input type="hidden" name="consecutivo_turno" id="consecutivo_turno" value="">
						<input type="hidden" name="secuencia" id="secuencia" value="">
						<input type="hidden" name="numero_turno" id="numero_turno" value="">
						<input type="hidden" name="sesion_modulo" id="sesion_modulo" value="<?php if (isset($_SESSION["MODULO"]) && $_SESSION["MODULO"] != '') {
							echo ($_SESSION["MODULO"]);
						} ?>">
						<input type="hidden" name="sesion_servicio" id="sesion_servicio" value="<?php if (isset($_SESSION["ID_SERVI"]) && $_SESSION["ID_SERVI"] != '') {
							echo ($_SESSION["ID_SERVI"]);
						} ?>">
						<input type="hidden" name="sesion_usuario" id="sesion_usuario" value="<?php if (isset($_SESSION["IDUSU"]) && $_SESSION["IDUSU"] != '') {
							echo ($_SESSION["IDUSU"]);
						} ?>">
					</form>
					<div id="num_atendidos" class="num_atendidos" style="width:100%;">
						Sincroniza y llama al primer turno
					</div>
					<ul>
						<li id="li_turno" style="display:none;background-color:#4790FF;">
							<a href="javascript:turno();">LLAMAR</a>
						</li>
						<li class="l3" id="li_inicio" style="display:none;background-color:#4790FF;">
							<a href="javascript:inicio();">INICIO</a>
						</li>
						<li class="l2" id="li_sig" style="display:none;background-color:#00a135;">
							<a href="javascript:siguiente();">SIGUIENTE</a>
						</li>
						<li class="l4" id="li_fin" style="display:none;background-color:#ec5353;">
							<a href="javascript:fin();">FIN</a>
						</li>
					</ul>

</div>
<div class="typs2">		
<div id="info_turnos" class="info">
						<p>
						<table width="100%" border="0" class="info_text" id="table_info">
							<tr>
								<td  class="labeltextdefault">Inicio de turno:</td>
								<td >
									<span style="font-size:18px;">
										<?php
										//echo($inicio_turno);
										echo ($row_RsFechaActual['FECHA']);
										?>
									</span>
								</td>
								<!-- <td width="160"> </td> -->
								<td rowspan="4">
									<form name="form2" id="form2" method="post" action="" style="margin-left: 10px">
										<table width="100%" border="0" id="T_sincronizado" style="display:none;">
											<tr>
												<td  align="right" class="labeltextdefault">Numero:</td>
												<td >
													<input class="inputtext" style="height:26px;" type="text"
														name="numero_sincronizado" placeholder="Numero de turno"
														onKeyPress='return acceptNum(event)' ; id="numero_sincronizado"
														value="" size="15">
												</td>
												<td ><a class="btn btn-xs btn-default"
														href="javascript:f_sincronizar();" class="sbttn"
														style="background:#267BA3;color:white; border: none">Enviar</a>
												</td>
											</tr>
											<tr>
												<td align="right"> Letra:</td>
												<td>
													<input class="inputtext" type="text" style="height:26px;"
														placeholder="Letra de turno (opcional)"
														name="letra_sincronizada" id="letra_sincronizada"
														onblur="this.value = this.value.toUpperCase();" size="15">
												</td>
												<td><a class="btn btn-xs btn-default" id="ocultarsincr" class="sbttn"
														href="javascript:OcultarSincr()">Cerrar</a></td>
											</tr>
											
										</table>
									</form>
								</td>
							</tr>
							<tr>
								<td>Cupos de turno:</td>
								<td>
								<?php
										echo ($cupo_turno);
										?>
								</td>
							</tr>
							<tr>
								<td class="labeltextdefault">Valor Sincronizado:</td>
								<td>
									<div id="dato_sincronizado">
										<?php //echo($turno_sincronizado); ?>
									</div>
								</td>
							</tr>
							<tr>
								<td class="labeltextdefault">Turno Actual</td>
								<td>
									<div id="turno_actual"></div>

								</td>
							</tr>

						</table>
						</p>
						</div>
						<div id="usuariosenlinea" class="news">
					<p align="center"><b>USUARIOS CONECTADOS AL SISTEMA</b></p>
					<table id="listausuarios">
						<tr>
							<td style="margin-right: 10px;">Persona</td>
							<td style="margin-right: 10px;">Modulo</td>
						</tr>
					</table>
				</div>
			
			</div>	

</div> 

		<div class="footerHome"></div>
</div>

	<!-- WARRPER END -->
	<?php
	$query_RsComprobarDataLog = "SELECT * FROM MODULOS 
		                             where MODUESTA = 1
									  AND  MODUUSUA = '" . $_SESSION["IDUSU"] . "'
									  AND  MODUID   = '" . $_SESSION["MODULO"] . "'";
	$RsComprobarDataLog = mysqli_query($turnos, $query_RsComprobarDataLog);
	//$row_RsComprobarDataLog = mysqli_fetch_assoc($RsComprobarDataLog);		
	$totalRows_RsComprobarDataLog = mysqli_num_rows($RsComprobarDataLog);
	if ($totalRows_RsComprobarDataLog == 0) {
		// header("location: logout.php");
	}
	?>
</body>

</html>