<?php

//require_once('seguridad.php');
require_once('Connections/db.php');
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

// mysqli_select_db($turnos, $database_turnos);

$query_RsParams = "SELECT PARAVALOR as NODESERVER,
                          (SELECT PARAVALOR 
                             FROM params_app 
                            WHERE PARACODI = '2') as NODEPORT
                     FROM params_app 
                    WHERE PARACODI = '1'";
$RsParams = mysqli_query($turnos, $query_RsParams) or die(mysqli_error($turnos));
$row_RsParams = mysqli_fetch_assoc($RsParams);
$nodeserver = $row_RsParams['NODESERVER'];
$nodeport = $row_RsParams['NODEPORT'];

$query_RsModulo = "SELECT MODUID as CODIGO,
                          MODUNOMB as NOMBRE
                     FROM modulos 
                    WHERE MODUTIPO = '2'";
$RsModulo = mysqli_query($turnos, $query_RsModulo) or die(mysqli_error($turnos));
$totalRows_RsModulo = mysqli_num_rows($RsModulo);
$row_RsModulo = mysqli_fetch_assoc($RsModulo);
// echo($totalRows_RsModulo)
?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
		rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&display=swap" rel="stylesheet">

	<style>
		body {
			margin: 0;
			padding: 0;
			background-color: #f8f8f8;
			width: 100vw;
			height: 100vh;
			overflow: hidden;
			font-family: "Sarabun", sans-serif;
			font-style: normal;
		}


		.logo {
			font-size: 24px;
			font-weight: bold;
		}

		.contentLogin {
			background-color: #fff;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			margin: auto auto;
			width: fit-content;
		}

		.label-login {
			font-weight: normal;
			font-size: 17px;
		}

		.input-login {
			width: 100%;
			padding: 10px;
			margin-top: 5px;
			border: 1px solid #ccc;
			border-radius: 5px;
		}

		.tableLogin {
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.button-login {
			width: 100%;
			padding: 10px;
			background-color: #007bff;
			color: #fff;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			
			font-size: 17px;
			font-weight: normal;
			transition: background-color 0.3s ease;
		}

		.button-login:hover {
			background-color: #0056b3;
		}


		.footer {
			text-align: center;
			margin-top: 20px;
			padding: 20px;
			background-color: #f8f8f8;
			border-top: 1px solid #ccc;
		}
	</style>
	<script type="text/javascript">
		function validar() {
			usuario = document.getElementById('usuario').value;
			password = document.getElementById('password').value;
			modulo = document.getElementById('modulo').value;
			if (usuario == '') {
				inlineMsg('usuario', 'debe ingresar su nombre de usuario.', 3);
				return false;
			}
			if (password == '') {
				inlineMsg('password', 'debe ingresar su contraseña de ingreso.', 3);
				return false;
			}
			if (modulo == '') {
				inlineMsg('modulo', 'debe ingresar el modulo.', 3);
				return false;
			}

			document.formlogin.action = "login.php";

		}

		function Ingreso() {
			document.formlogin.action = "reset_user.php";
			document.formlogin.submit();
		}
	</script>
	<script>
		function getDataServer(url, vars) {
			var xml = null;
			try {
				xml = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (expeption) {
				xml = new XMLHttpRequest();
			}
			xml.open("GET", url + vars, false);
			xml.send(null);
			if (xml.status == 404) alert("Url no valida");
			return xml.responseText;
		}
		var node_on = '0';
		var socket;
		$(document).ready(function () {
			/*var v_dato = getDataServer("localhost:8000","?tipoGuardar=comprobarnode");
			alert(v_dato);
			*/

			socket = new io.connect('<?php echo ($nodeserver); ?>', {
				port: <?php echo ($nodeport); ?>
			});

			socket.on('connect', function () {
				//console.log('Client has connected to the server!');
				setTimeout(function () {
					try {
						node_on = '1';
					} catch (exc) {
					}
				}, 300);
			});

			socket.on('disconnect', function () {
				//
			});

			setTimeout(function () {
				try {
					if (node_on == '0') {
						divnodeoff = '<div id="nodeoff" class="nodeoff"></div>';
						divnodemsg = '<div class="nodeoff-info"><p>No hay conexion con el servidor en:<br><span><a target="_blank" href="http://<?php echo ($nodeserver . ":" . $nodeport); ?>"><?php echo ($nodeserver . ":" . $nodeport); ?></a></span><br> verifique e intente cargar <br>nuevamente la pagina</p><p><a href="" class="btn btn-danger btn-lg">Actualizar</a></p></div>';
						$("#usuario").val('');
						$("#password").val('');
						$("#modulo").val('');
						$('body').append(divnodeoff);
						$('body').append(divnodemsg);
					}
				} catch (exc) {
				}
			}, 2600);

		});



	</script>
</head>

<body>
	<div style="width:100%; height: 100%;display:flex;flex-direction:column; justify-content:center;">

		<div class="header">
			<div class="logo">
				<p>Sistema de turnos</p>
			</div>
		</div>

		<div class="contentLogin">

			<form name="formlogin" id="formlogin" action="" method="post">
				<table align="center" width="100%" class="tableLogin">
					<tr>
						<td class="label-login">Usuario</td>
						<td><input class="input-login" type="text" name="usuario" id="usuario" value="" size="40"
								placeholder="Nombre de Usuario"></td>
					</tr>
					<tr>
						<td class="label-login">Clave</td>
						<td><input class="input-login" type="password" name="password" id="password" value="" size="40"
								placeholder="Password"></td>
					</tr>
					<tr>
						<td class="label-login">Modulo</td>
						<td>
							<select name="modulo" id="modulo" class="input-login">
								<option value="">Seleccione</option>
								<?php
								if ($totalRows_RsModulo > 0) {
									do {
										?>
										<option value="<?php echo ($row_RsModulo['CODIGO']); ?>">
											<?php echo ($row_RsModulo['NOMBRE']); ?>
										</option>
										<?php
									} while ($row_RsModulo = mysqli_fetch_assoc($RsModulo));
								}
								?>
						</td>
					</tr>
					<tr style="display:flex;justify-content: center; width: 100%;margin-top: 20px;">
						<td><input class="button-login" type="submit" value="Ingresar" onclick="return validar();"></td>
					</tr>
				</table>
			</form>

		</div>
		<div class="footerHome"></div>

	</div>
</body>

</html>