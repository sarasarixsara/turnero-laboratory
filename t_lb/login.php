<?php
require_once('Connections/db.php');
if (!isset($_SESSION)) {
	session_start();
}

if (trim($_POST["usuario"]) != "" && trim($_POST["password"]) != "") {
	// Puedes utilizar la funcion para eliminar algun caracter en especifico
	//$usuario = strtolower(quitar($HTTP_POST_VARS["usuario"]));
	//$password = $HTTP_POST_VARS["password"];

	// o puedes convertir los a su entidad HTML aplicable con htmlentities
	$usuario = strtolower(htmlentities($_POST["usuario"], ENT_QUOTES));
	$password = $_POST["password"];

	//     mysql_select_db($database_turnos,$turnos);
	//$turnos = mysqli_connect($hostname_turnos, $username_turnos, $password_turnos, $database_turnos);
	$turnos->select_db("tur_lb");

	// Verificar la conexión
	if (mysqli_connect_errno()) {
		echo "Error al conectar a MySQL: " . mysqli_connect_error();
		exit();
	}

	// $result = mysql_query('
	// SELECT DISTINCT U.USUAID IDUSUARIO,
	//                 P.PERSCEDU ID_PERSONA,
	// 				U.USUACONT PASS,
	// 				U.USUAESTA ESTADO,
	// 				P.PERSNOMB PERSONA,
	// 				R.ROLENOMB ROL,
	// 				R.ROLEID IDROL
	// FROM            PERSONAS P,
	// 				ROLES R,
	// 				USUARIOS U
	// WHERE           U.USUAIDPE = P.PERSCONS
	//     AND         U.USUAIDRO = R.ROLEID
	//     AND         U.USUANOMB=\'' . $usuario . '\'');

	$query = "SELECT DISTINCT U.USUAID AS IDUSUARIO,
	P.PERSCEDU AS ID_PERSONA,
	U.USUACONT AS PASS,
	U.USUAESTA AS ESTADO,
	P.PERSNOMB AS PERSONA,
	R.ROLENOMB AS ROL,
	R.ROLEID AS IDROL
		FROM PERSONAS AS P
		INNER JOIN USUARIOS AS U ON U.USUAIDPE = P.PERSCONS
		INNER JOIN ROLES AS R ON U.USUAIDRO = R.ROLEID
		
		WHERE U.USUANOMB = ?";


	$statement = mysqli_prepare($turnos, $query);
	mysqli_stmt_bind_param($statement, "s", $usuario);
	mysqli_stmt_execute($statement);
	$result = mysqli_stmt_get_result($statement);


	//exit($result);	

	if ($row = mysqli_fetch_array($result)) {
		if ($row["ESTADO"] != 1 or isset($_SESSION["IDUSU"]) == $row['IDUSUARIO'] || isset($_SESSION["IDUSU"]) == '') {
			if ($row["PASS"] == $password) {

				if (isset($_SESSION["MODULO"]) == $_POST['modulo'] or isset($_SESSION["IDUSU"]) == $row['IDUSUARIO']) {

					$query_RsActualizarModulo = "
			UPDATE modulos SET MODUESTA = '0' WHERE MODUID ='" . $_POST['modulo'] . "'";
					$RsActualizarModulo = mysqli_query($turnos, $query_RsActualizarModulo) or die(mysqli_error($turnos));

					$query_RsActualizarUsuario = "
		   UPDATE usuarios SET USUAESTA = '0' WHERE USUAID ='" . $row['IDUSUARIO'] . "'";
					$RsActualizarUsuario = mysqli_query($turnos, $query_RsActualizarUsuario) or die(mysqli_error($turnos));

					echo '<SCRIPT LANGUAGE="javascript">';

					echo '
		alert ("Intente Nuevamente su cuenta se cerro inesperadamente o modulo ya estan en servicio");
		location.href = "logout.php";
            </SCRIPT>';

				} else {
					$_SESSION["USU_AUTENTICADO"] = $row['ID_PERSONA'];
					$_SESSION["USU_AUT_NOMB"] = $row['PERSONA'];
					$_SESSION["AUTENTICADO"] = 'SI';
					$_SESSION["ROLNOM"] = $row['ROL'];
					$_SESSION["ROL"] = $row['IDROL'];
					$_SESSION["IDUSU"] = $row['IDUSUARIO'];
					$_SESSION["MODULO"] = $_POST['modulo'];
					$_SESSION["TABLE"] = 'turnosfull';
					$_SESSION["TABLE2"] = 'turnosfull';

					$query_RsNombreModulo = "SELECT MODUNOMB, MODUSALA  from modulos where MODUID ='" . $_POST['modulo'] . "'";
					$RsNombreModulo = mysqli_query($turnos, $query_RsNombreModulo) or die(mysqli_error($turnos));
					$row_RsNombreModulo = mysqli_fetch_assoc($RsNombreModulo);
					$_SESSION["NOMBRE_MODULO"] = $row_RsNombreModulo['MODUNOMB'];
					$_SESSION["SALA"] = $row_RsNombreModulo['MODUSALA'];
					//exit($query_RsNombreModulo);
					$query_RsActualizarModulo = "
			UPDATE modulos SET MODUESTA = '1',
          			           MODUUSUA = '" . $row['IDUSUARIO'] . "'
			WHERE MODUID ='" . $_POST['modulo'] . "'";
					$RsActualizarModulo = mysqli_query($turnos, $query_RsActualizarModulo) or die(mysqli_error($turnos));

					/*$query_RsActualizarModuloU="
																																									UPDATE modulos SET MODUUSUA = '".$row['IDUSUARIO']."' WHERE MODUID ='".$_POST['modulo']."'";
																																									$RsActualizarModuloU = mysql_query($query_RsActualizarModuloU, $turnos) or die(mysql_error());			
																																									*/
					$query_RsActualizarUsuario = "
		   UPDATE usuarios SET USUAESTA = '1' WHERE USUAID ='" . $row['IDUSUARIO'] . "'";
					$RsActualizarUsuario = mysqli_query($turnos, $query_RsActualizarUsuario) or die(mysqli_error($turnos));


					//Ingreso exitoso, ahora sera dirigido a la pagina principal. 
					echo '<SCRIPT LANGUAGE="javascript">
            location.href = "sevicio.php";
            </SCRIPT>';
				}
			} else {
				//ingreso con contraseña incorrecta
				echo '<SCRIPT LANGUAGE="javascript">';
				echo 'alert ("Password incorrecto");		   
            location.href = "index.php";
            </SCRIPT>';

			}
		} else {
			//ingreso con contraseña incorrecta
			echo '<SCRIPT LANGUAGE="javascript">';
			echo 'alert ("su cuenta de usuario ya esta en uso");		   
            location.href = "index.php";
            </SCRIPT>';
		}
	} else {
		//el usuario no existe en base de datos
		echo '<SCRIPT LANGUAGE="javascript">';
		echo 'alert ("Usuario no existente ");
            location.href = "index.php";
            </SCRIPT>';
	}
	mysql_free_result($result);
} else {
	echo '<SCRIPT LANGUAGE="javascript">';
	echo 'alert ("Debe especificar un usuario y password");
            location.href = "index.php";
            </SCRIPT>';
}
//mysql_close();
?>