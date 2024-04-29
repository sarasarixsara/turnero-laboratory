<?php
	require_once('Connections/db.php');
if (!isset($_SESSION)) {
  session_start();
}

if(trim($_POST["usuario"]) != "" && trim($_POST["password"]) != "")
{
    // Puedes utilizar la funcion para eliminar algun caracter en especifico
    //$usuario = strtolower(quitar($HTTP_POST_VARS["usuario"]));
    //$password = $HTTP_POST_VARS["password"];
    
    // o puedes convertir los a su entidad HTML aplicable con htmlentities
    $usuario = strtolower(htmlentities($_POST["usuario"], ENT_QUOTES));    
    $password = $_POST["password"];
	
     mysql_select_db($database_turnos,$turnos);
	 
    $result = mysql_query('
	SELECT DISTINCT U.USUAID IDUSUARIO,
	                P.PERSCEDU ID_PERSONA,
					U.USUACONT PASS,
					U.USUAESTA ESTADO,
					P.PERSNOMB PERSONA,
					R.ROLENOMB ROL,
					R.ROLEID IDROL
    FROM            PERSONAS P,
					ROLES R,
					USUARIOS U
    WHERE           U.USUAIDPE = P.PERSCONS
        AND         U.USUAIDRO = R.ROLEID
        AND         U.USUANOMB=\''.$usuario.'\'');
	
//exit($result);	
    
	if($row = mysql_fetch_array($result)){
		if($row["ESTADO"] != 1 or isset($_SESSION["IDUSU"])== $row['IDUSUARIO'] || isset($_SESSION["IDUSU"])=='' )
		{
	       if($row["PASS"] == $password){
          
            if (isset($_SESSION["MODULO"])== $_POST['modulo'] or isset($_SESSION["IDUSU"])== $row['IDUSUARIO'] ){
			
			$query_RsActualizarModulo="
			UPDATE modulos SET MODUESTA = '0' WHERE MODUID ='".$_POST['modulo']."'";
			$RsActualizarModulo = mysql_query($query_RsActualizarModulo, $turnos) or die(mysql_error());
			
			$query_RsActualizarUsuario="
		   UPDATE usuarios SET USUAESTA = '0' WHERE USUAID ='".$row['IDUSUARIO']."'";
			$RsActualizarUsuario = mysql_query($query_RsActualizarUsuario, $turnos) or die(mysql_error());
			
			echo '<SCRIPT LANGUAGE="javascript">';
			
		echo '
		alert ("Intente Nuevamente su cuenta se cerro inesperadamente o modulo ya estan en servicio");
		location.href = "logout.php";
            </SCRIPT>';
			
			}else{
			$_SESSION["USU_AUTENTICADO"] = $row['ID_PERSONA'];
			$_SESSION["USU_AUT_NOMB"] = $row['PERSONA'];
			$_SESSION["AUTENTICADO"] ='SI';
			$_SESSION["ROLNOM"] =$row['ROL'];
			$_SESSION["ROL"] =$row['IDROL'];
			$_SESSION["IDUSU"] =$row['IDUSUARIO'];
			$_SESSION["MODULO"] =$_POST['modulo'];
			$_SESSION["TABLE"] ='turnosfull';
			$_SESSION["TABLE2"] ='turnosfull';
			
			$query_RsNombreModulo="SELECT MODUNOMB, MODUSALA  from modulos where MODUID ='".$_POST['modulo']."'";
			$RsNombreModulo = mysql_query($query_RsNombreModulo, $turnos) or die(mysql_error());
		    $row_RsNombreModulo = mysql_fetch_assoc($RsNombreModulo);
			$_SESSION["NOMBRE_MODULO"] = $row_RsNombreModulo['MODUNOMB'];
			$_SESSION["SALA"]          = $row_RsNombreModulo['MODUSALA'];
			//exit($query_RsNombreModulo);
			$query_RsActualizarModulo="
			UPDATE modulos SET MODUESTA = '1',
          			           MODUUSUA = '".$row['IDUSUARIO']."'
			WHERE MODUID ='".$_POST['modulo']."'";
			$RsActualizarModulo = mysql_query($query_RsActualizarModulo, $turnos) or die(mysql_error());
			
			/*$query_RsActualizarModuloU="
			UPDATE modulos SET MODUUSUA = '".$row['IDUSUARIO']."' WHERE MODUID ='".$_POST['modulo']."'";
			$RsActualizarModuloU = mysql_query($query_RsActualizarModuloU, $turnos) or die(mysql_error());			
			*/
			$query_RsActualizarUsuario="
		   UPDATE usuarios SET USUAESTA = '1' WHERE USUAID ='".$row['IDUSUARIO']."'";
			$RsActualizarUsuario = mysql_query($query_RsActualizarUsuario, $turnos) or die(mysql_error());
	   
	   
           //Ingreso exitoso, ahora sera dirigido a la pagina principal. 
		   echo '<SCRIPT LANGUAGE="javascript">
            location.href = "sevicio.php";
            </SCRIPT>';
             }		
        }else{
		//ingreso con contraseña incorrecta
			echo '<SCRIPT LANGUAGE="javascript">';
		echo 'alert ("Password incorrecto");		   
            location.href = "index.php";
            </SCRIPT>';	
		
        }
		}else{
		//ingreso con contraseña incorrecta
			echo '<SCRIPT LANGUAGE="javascript">';
		echo 'alert ("su cuenta de usuario ya esta en uso");		   
            location.href = "index.php";
            </SCRIPT>';			
		}
    }else{
	//el usuario no existe en base de datos
        echo '<SCRIPT LANGUAGE="javascript">';
		echo 'alert ("Usuario no existente ");
            location.href = "index.php";
            </SCRIPT>';
    }
    mysql_free_result($result);
}else{
	 echo '<SCRIPT LANGUAGE="javascript">';
		echo 'alert ("Debe especificar un usuario y password");
            location.href = "index.php";
            </SCRIPT>';
}
//mysql_close();
?>