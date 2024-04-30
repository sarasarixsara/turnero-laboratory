<?php
//This product includes PHP software, freely available from <http://www.php.net/software/>

require_once ('Connections/db.php');

// mysql_select_db($database_turnos, $turnos);
// @mysqli_query("SET collation_connection = utf8_general_ci;");
// mysqli_query ("SET NAMES 'utf8'");

$sonido = "";
$query_RsDatosBienvenida = "DELETE FROM turnos where TURNIDUS = '0'";
$RsDatosBienvenida = mysqli_query($turnos, $query_RsDatosBienvenida);

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
//$urlnode = 'http://'.$nodeserver.':'.$nodeport;	

$query_RsllAMADOTV = "SELECT      MODUID  CODIGO_MODULO,
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
$RsllAMADOTV = mysqli_query($turnos, $query_RsllAMADOTV);
$row_RsllAMADOTV = mysqli_fetch_assoc($RsllAMADOTV);
$totalRows_RsllAMADOTV = mysqli_num_rows($RsllAMADOTV);

// if ($totalRows_RsllAMADOTV > 0) {
// 	$sonido = 'autoplay';
// } else {
// 	$sonido = '';
// }
?>
<!DOCTYPE html>
<html>

<head>
	<title>PANTALLA TV</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script src="jquery/jquery-1.10.2.min.js"></script>
	<script src="socket/socket.io.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>

	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
		integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
		integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
		crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
		integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
		crossorigin="anonymous"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&family=Sarabun:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
		rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Catamaran:wght@100..900&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<script>
		var socket;
		$(document).ready(function () {

			var sound = new Howl({
				src: ['sounds/Alert.mp3'] // Cambia 'ruta/al/archivo-de-sonido.mp3' por la ubicación de tu archivo de sonido
			});

			socket = new io.connect('<?php echo ($nodeserver); ?>', {
				port: <?php echo ($nodeport); ?>
			});

			socket.on('connect', function () {
				console.log('Client has connected to the server!');
			});

			socket.on('message', function (data) {
				$('#chat').prepend('<div>' + data.usu + ': ' + data.msg + '</div>');
			});



			socket.on('PANTALLATV_LB', function (data) {
				//alert(data);
				//$('#chat').append('<div>' + data.usu + ': ' + data.msg + '</div>');
				//$('#chat').append('<div>' + data + '</div>');
				v_campos = data.split('!');
				for (var i = 0; i < v_campos.length - 1; i++) {
					var datos = v_campos[i].split('/');
					var codigo_modulo = datos[0];
					var nombre_modulo = datos[1];
					var turno = datos[2];
					var consecutivo = datos[3];
					var color = datos[4]; //alert('2)'+color);
					var nombre_servicio = datos[5];

					var nombre_div = 'div_turn_' + turno + '_' + codigo_modulo + '_' + consecutivo;
					//alert(nombre_div);
					var existe = document.getElementById(nombre_div);
					//alert(existe);
					//alert('hi');
					if (codigo_modulo != '0') {
						if (existe == null) {
							//alert('there no enter');
							//var turnosadd=$('div.contenedor_turno');
							$('.contenedor_turno').each(function (index) {
								var turnoexist = $(this).attr('id');
								var campdiv = turnoexist.split('_');
								var nombre_moduloE = campdiv[1];
								var turnoE = campdiv[2];
								var consecutivoE = campdiv[4];
								var codigo_moduloE = campdiv[3];
								// alert(codigo_modulo+' - '+codigo_moduloE);
								if (codigo_modulo == codigo_moduloE) {
									$(this).remove();
								}

							})

							sound.play();

							function contarFilas() {
								return $("#turnero tr").length;
							}

							// Función para aplicar el estilo según el número de filas
							function aplicarEstilo() {
								var numFilas = contarFilas();
								console.log(numFilas);
								if (numFilas >= 5) {
									$("#turnero").addClass("tabla-llena");
									console.log('la tabla esta llena');
								} else {
									$("#turnero").removeClass("tabla-llena");
								}
							}

							// Llamada a la función para aplicar el estilo al cargar la página
							$(document).ready(function () {
								aplicarEstilo();
							});

							$("#turnero").prepend("<tr class='contenedor_turno' id='div_turn_" + turno + "_" + codigo_modulo + "_" + consecutivo + "'><td class='turnoA' align='center'>" + turno + "</td><td align='center' class='moduloA'>" + nombre_modulo + "</td></tr>");
							aplicarEstilo();
							document.getElementById('peticiones_ok').value = parseInt(document.getElementById('peticiones_ok').value) + 1;
							if (parseInt(document.getElementById('peticiones_ok').value) > 0) {
								document.getElementById('peticiones_ok').value = parseInt(document.getElementById('peticiones_ok').value) + 1;
							}
							//if(parseInt(document.getElementById('peticiones_ok').value)>7){
							//alert('se recargara la pagina');
							// document.form1.submit();
							//}									 
						}
					}


				}
			});

			socket.on('disconnect', function () {
				console.log('sin conexion');
				$('#chat').html('');
			});
		});

	</script>


</head>

<body>
	<div class="containerTV">
		<img src="images/imagen-fondo.jpeg" style="width: 100%; height: 100%; z-index: -10; position: absolute" alt="">
		<div class="contentTurns">
			<form name="form1" method="post" action="">
				<table id="turnero">
					<thead>
						<tr>
							<td align="center" width="200" id="turno" class="turno">
								Turno
							</td>
							<td align="center" width="200" id="modulo" class="modulo">
								Modulo
							</td>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($totalRows_RsllAMADOTV > 0) { // Show if recordset not empty 
							do {
								?>

								<tr class="contenedor_turno"
									id="div_turn_<?php echo ($row_RsllAMADOTV['TURNO']); ?>_<?php echo ($row_RsllAMADOTV['CODIGO_MODULO']); ?>_<?php echo ($row_RsllAMADOTV['CONSECUTIVO']); ?>">
									<td width="200" align="center" height="100" class="turnoA">
										<?php echo ($row_RsllAMADOTV['TURNO']); ?>
									</td>
									<td width="200" align="center" height="100" class="moduloA">
										<?php echo ($row_RsllAMADOTV['MODULO']); ?>

									</td>
								</tr>
								<?php /*</div>*/ ?>
								<?php
							} while ($row_RsllAMADOTV = mysqli_fetch_assoc($RsllAMADOTV));
						}
						?>
					</tbody>
					<input type="hidden" name="peticiones_ok" id="peticiones_ok" value="0">
				</table>
			</form>
		</div>
		<div class="footerTV">
			<div class="slider-text">
				<div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<div class="carousel-item active"
							style="color:white; font-weight: bolder;font-size: 25px; text-align:center">
							Exámenes de rutina y especializados</div>
						<div class="carousel-item"
							style="color:white; font-weight: bolder; font-size: 25px; text-align:center">
							Citología - Glucometría - Pruebas de embarazo
						</div>

					</div>
				</div>
			</div>
			<div
				style="text-align: center; color: white; bottom: 0; position: fixed; right: 5%; font-weight: bolder; margin-bottom:15px; font-size: 25px">
				Domicilios <br> 3238104349

			</div>

		</div>
	</div>
	</div>

</body>

</html>
<style type="text/css">
	body {
		font-family: "Sarabun", sans-serif;
		font-style: normal;
		margin: 0;
		padding: 0;
		height: 100vh;
		width: 100vw;
		overflow: hidden;
		background-image: url("images/fondo.png");
		background-size: cover;
		background-repeat: no-repeat;
	}

	.containerTV {
		height: 100vh;
		width: 100vw;
		margin: 0;

	}

	.tabla-llena {
		background-color: aqua !important;
	}

	.slider-text {
		width: 60%;
		height: 4rem;
		bottom: 0;
		position: fixed;
		background-color: #157bba;
		border-top-right-radius: 100px;
		display: flex;
		justify-content: center;
		align-items: center
	}

	.contentTurns {
		width: 100%;
		max-height: 60%;
		right: -70%;
		top: 9rem;
		position: relative;
	}

	.footerTV {
		position: relative;
		width: 100%;
		height: 100%;
	}

	.video {
		width: 70%;
		padding: 20px;
		display: flex;
		justify-content: center;
		align-items: center;

	}

	#myVideo {
		border-top-right-radius: 15px;
		border-bottom-right-radius: 15px;
	}

	h4 {
		color: navy;
		font-weight: bold;
		text-align: center;
		margin-bottom: 30px;
		margin-top: 30px;
	}

	table {

		max-width: 100%;
		margin-left: 0px;
		height: 100%;
		border-collapse: separate;
		border-spacing: 10px;
		background: white;
		border-top-left-radius: 30px;
		border-bottom-left-radius: 30px;
		border-color: #3bb1f2;
		border-width: 7px;
		border-style: solid;
		padding: 15px 30px 15px 15px;


	}

	#div_title {
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 40px 55px;
	}



	td {
		padding: 0 15px;
		text-align: center;
		border: none;
		border-radius: 20px;
		height: fit-content;
		background-color: #157bba;
		color: white;
		width: auto;

	}

	.turnoA,
	.moduloA,
	.turno,
	.modulo {
		font-size: 60px;
		font-weight: bolder;
	}

	#turno,
	#modulo {
		background: #3bb1f2;
		color: white;
		border: 2px solid white;
		font-size: 50px !important;
	}
</style>