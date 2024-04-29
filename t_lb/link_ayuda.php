<?php
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );

//require_once('seguridad.php');
require_once('Connections/db.php');
if (!isset($_SESSION)) {
  		session_start();
	}
	
// mysql_select_db($database_turnos, $turnos);
// @mysqli_query("SET collation_connection = utf8_general_ci;");
// mysqli_query ("SET NAMES 'utf8'");

$tipoguardar='';
if(isset($_GET['tipoguardar']) && $_GET['tipoguardar']!=''){
$tipoguardar=$_GET['tipoguardar'];
}

function TablamodeAyuda($turnos,$jornada){

		$query_RsServiciosMultiples="SELECT M.MODUID MODULO,
 		                                    M.MODUNOMB MODULO_DES,
 											M.MODUUSUA USUARIO, 
											M.MODUSERV SERVICIOS,
											P.PERSNOMB PERSONA_DES
									FROM modulos M, 
									    personas P,
									    usuarios U
									where M.MODUUSUA = U.USUAID 
									  AND U.USUAIDPE = P.PERSCONS 
									  AND M.MODUMULT = 1
									  AND M.MODUSALA = '".$_GET['sala']."'";
		$RsServiciosMultiples = mysqli_query($turnos,$query_RsServiciosMultiples) ;
		$row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples);		
		$totalRows_RsServiciosMultiples = mysqli_num_rows($RsServiciosMultiples);
	/*	
		$query_RsServiciosMultiples="SELECT M.MODUUSUA USUARIO, M.MODUSERV SERVICIOS FROM MODULOS M where M.MODUMULT = 1";
		$RsServiciosMultiples = mysqli_query($turnos,$query_RsServiciosMultiples) ;
		$row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples);		
		$totalRows_RsServiciosMultiples = mysqli_num_rows($RsServiciosMultiples);
*/

		 $contmult='';
		 $s="'";
		if($totalRows_RsServiciosMultiples>0){
		$t=0;
		$q=0;
		   do{
		      $services = explode(",",$row_RsServiciosMultiples['SERVICIOS']);
			  for($z=0; $z<count($services); $z++){
			    $query_RsDatosServicio="SELECT *,(SELECT COUNT(TURNCONS)
								      FROM turnos T
								     where T.TURNTURN = '".$jornada."'
									  AND  T.TURNIDES = '2'
									  and  T.TURNIDUS = '".$row_RsServiciosMultiples['USUARIO']."'
									  and  T.TURNSERV = '".$services[$z]."'
									  AND  DATE_FORMAT(T.TURNFEFI, '%d') = DATE_FORMAT(SYSDATE(),'%d')) REALIZADOS FROM servicios s where s.servid = '".$services[$z]."'";
				$RsDatosServicio = mysqli_query($turnos,$query_RsDatosServicio);
				$row_RsDatosServicio = mysqli_fetch_assoc($RsDatosServicio);		
				$totalRows_RsDatosServicio = mysqli_num_rows($RsDatosServicio);			  
				$q++;
			  if($totalRows_RsDatosServicio>0){	
			  if($q%2==0){
			  $estilomult="alt2";
			  }
			  else{
			  $estilomult="alt";
			  }
			  
			  $armarlinkmult=$s.$row_RsServiciosMultiples['USUARIO'].$s.",".$s.$row_RsServiciosMultiples['MODULO'].$s.",".$s.$row_RsDatosServicio['SERVID'].$s;
			   if($row_RsDatosServicio['REALIZADOS']>0){
			  $contmult=$contmult." 
			   <tr class=".$estilomult.">
			    <td><b>".$row_RsServiciosMultiples['PERSONA_DES']."</b></td>
			    <td>".$row_RsServiciosMultiples['MODULO_DES']."</td>
			    <td>".$row_RsDatosServicio['SERVNOMB']."</td>
			    <td>".$row_RsDatosServicio['SERVTUTA']."</td>
			    <td align='center'>".$row_RsDatosServicio['REALIZADOS']."</td>
				<td><ul><li><a href=\"javascript: Ayudar(".$armarlinkmult.");\">Ayudar</a></li></ul></td>
			   </tr>";
			    }
			   }
			  }
		    }while($row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples));		
		}
		
		return $contmult;
}

if($tipoguardar=='ModeAyuda')
{
  if(isset($_SESSION["ID_SERVI"]) && $_SESSION["ID_SERVI"]!=''){
  $s="'";
		$query_RsHora="SELECT CURTIME() HORA,
		                     date_format(sysdate(),'%H') num_hora,
		                     date_format(sysdate(),'%d') dia";
		$RsHora = mysqli_query($turnos,$query_RsHora);
		$row_RsHora = mysqli_fetch_assoc($RsHora);
		
		$horaselect=$row_RsHora['HORA'];
		$partshora=explode(':',$horaselect);
		$hora    = $partshora[0];
		$minuto  = $partshora[1];
		
		  $table="<table  class='datagrid'>
		           <tr>
				    <td colspan='6'><ul><li><a href='javascript: generarAyuda();'>Actualizar</a></li><li><a href='javascript: ModoNormal();'>Modo normal</a></li></ul></td>
				   </tr>
				   <tr class='headerlabel'>
				     <td>Usuario</td>
					 <td>modulo</td>
					 <td>servicio</td>
					 <td>cupo</td>
					 <td>realizados</td>
					 <td></td>
				   </tr>
				 ";
		 $endtable="</table>";			
		
		if($hora>=12 ){
		
		$query_RsServicios="SELECT U.USUAID IDUSUARIO,
		                           U.USUAESTA ESTADO,
								   P.PERSNOMB PERSONA_DES,
								   M.MODUID   MODULO,
								   M.MODUNOMB MODULO_DES,
								   S.SERVID   SERVICIO,
								   S.SERVNOMB SERVICIO_DES,
                                   S.SERVTUMA TURNOS_AM,
								   S.SERVTUTA TURNOS_PM,
								   (SELECT COUNT(TURNCONS)
								      FROM turnos T
								     where T.TURNTURN = '2'
									  AND  T.TURNIDES = '2'
									  and  T.TURNIDUS = U.USUAID
									  AND  DATE_FORMAT(T.TURNFEFI, '%d') = DATE_FORMAT(SYSDATE(),'%d')) REALIZADOS
								   From usuarios  U,
							     personas  P,
								 modulos   M,
								 servicios S
						   WHERE U.USUAIDPE = P.PERSCONS
						     and M.MODUUSUA = U.USUAID
							 AND M.MODUSERV = S.SERVID
						     AND U.USUAESTA = '1'
							 AND M.MODUESTA = '1'
							 AND U.USUAID != '".$_SESSION["IDUSU"]."'
							 AND M.MODUMULT = 0
							 and (SELECT COUNT(TURNCONS)
								      FROM turnos T
								     where T.TURNTURN = '2'
									  AND  T.TURNIDES = '2'
									  and  T.TURNIDUS = U.USUAID
									  AND  DATE_FORMAT(T.TURNFEFI, '%d') = DATE_FORMAT(SYSDATE(),'%d')) >0 
							 AND M.MODUSALA = '".$_GET['sala']."'";
							 //ECHO($query_RsServicios);
		$RsServicios = mysqli_query($turnos,$query_RsServicios);
		$row_RsServicios = mysqli_fetch_assoc($RsServicios);		
		$totalRows_RsServicios = mysqli_num_rows($RsServicios);    
		  
	  
		  if($totalRows_RsServicios>0){
		 $cont="";
		 $i=0;
		    do{
			$i++;
			  if($i%2==0){
			  $estilo="alt2";
			  }
			  else{
			  $estilo="alt";
			  }
			   
			   $armarlink=$s.$row_RsServicios['IDUSUARIO'].$s.",".$s.$row_RsServicios['MODULO'].$s.",".$s.$row_RsServicios['SERVICIO'].$s;
			  	if($row_RsServicios['REALIZADOS']>0){
				$cont=$cont."
				<tr class='".$estilo."'>
				 <td>".$row_RsServicios['PERSONA_DES']."</td>
				 <td>".$row_RsServicios['MODULO_DES']."</td>
				 <td>".$row_RsServicios['SERVICIO_DES']."</td>
				 <td>".$row_RsServicios['TURNOS_PM']."</td>
				 <td>".$row_RsServicios['REALIZADOS']."</td>
				 <td><ul><li><a href=\"javascript: Ayudar(".$armarlink.");\">Ayudar</a></li></ul></td>
				</tr>
				";
				}
			 }while($row_RsServicios = mysqli_fetch_assoc($RsServicios));
			 /*for($i=0; $i<3; $i++){
			 $cont=$cont.$cont;
			 }*/
			 $contmult='';
		/*$query_RsServiciosMultiples="SELECT M.MODUID MODULO,
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
		$RsServiciosMultiples = mysqli_query($turnos,$query_RsServiciosMultiples) ;
		$row_RsServiciosMultiples = mysqli_fetch_assoc($RsServiciosMultiples);		
		$totalRows_RsServiciosMultiples = mysqli_num_rows($RsServiciosMultiples);
         */
        $contmult=TablamodeAyuda($turnos,'2');
		echo($table.$contmult.$cont.$endtable);
		  }else{
		  $contmult=TablamodeAyuda($turnos,'2');
		    //echo ("<table  class='datagrid'><tr class=''><td colspan='6'><ul><li><a href='javascript: generarAyuda();'>Actualizar</a></li><li><a href='javascript: ModoNormal();'> Regresar a Modo normal</a></li></ul></td></tr>".$contmult."</table>");
			echo($table.$contmult.$endtable);
		  }
		}else{
		
	$query_RsServicios="SELECT U.USUAID IDUSUARIO,
		                           U.USUAESTA ESTADO,
								   P.PERSNOMB PERSONA_DES,
								   M.MODUID   MODULO,
								   M.MODUNOMB MODULO_DES,
								   S.SERVID   SERVICIO,
								   S.SERVNOMB SERVICIO_DES,
                                   S.SERVTUMA TURNOS_AM,
								   S.SERVTUTA TURNOS_PM,
								   (SELECT COUNT(TURNCONS)
								      FROM turnos T
								     where T.TURNTURN = '1'
									  AND  T.TURNIDES = '2'
									  and  T.TURNIDUS = U.USUAID
									  AND  DATE_FORMAT(T.TURNFEFI, '%d') = DATE_FORMAT(SYSDATE(),'%d')) REALIZADOS
								   From usuarios  U,
							     personas  P,
								 modulos   M,
								 servicios S
						   WHERE U.USUAIDPE = P.PERSCONS
						     and M.MODUUSUA = U.USUAID
							 AND M.MODUSERV = S.SERVID
						     AND U.USUAESTA = '1'
							 AND M.MODUESTA = '1'
							 AND U.USUAID != '".$_SESSION["IDUSU"]."'
							 AND M.MODUMULT = 0
							 and (SELECT COUNT(TURNCONS)
								      FROM turnos T
								     where T.TURNTURN = '1'
									  AND  T.TURNIDES = '2'
									  and  T.TURNIDUS = U.USUAID
									  AND  DATE_FORMAT(T.TURNFEFI, '%d') = DATE_FORMAT(SYSDATE(),'%d')) >0
							 AND M.MODUSALA = '".$_GET['sala']."'
							 ";
							 //ECHO($query_RsServicios);
		$RsServicios = mysqli_query($turnos,$query_RsServicios);
		$row_RsServicios = mysqli_fetch_assoc($RsServicios);		
		$totalRows_RsServicios = mysqli_num_rows($RsServicios);    
		  if($totalRows_RsServicios>0){
		  $table="<table  class='datagrid'>
		           <tr>
				    <td colspan='6'><ul><li><a href='javascript: generarAyuda();'>Actualizar</a></li><li><a href='javascript: ModoNormal();'>Modo normal</a></li></ul></td>
				   </tr>
				   <tr class='headerlabel'>
				     <td>Usuario</td>
					 <td>modulo</td>
					 <td>servicio</td>
					 <td>cupo</td>
					 <td>realizados</td>
					 <td></td>
				   </tr>
				 ";
		 $endtable="</table>";
		 $cont="";
		 $i=0;
		 $armarlink='';
		    do{
			$i++;
			  if($i%2==0){
			  $estilo="SBRA2";
			  }
			  else{
			  $estilo="SBRA";
			  }
			   $s="'";
			   $armarlink=$s.$row_RsServicios['IDUSUARIO'].$s.",".$s.$row_RsServicios['MODULO'].$s.",".$s.$row_RsServicios['SERVICIO'].$s;
			  	if($row_RsServicios['REALIZADOS']>0){
				$cont=$cont."
				<tr class='".$estilo."'>
				 <td>".$row_RsServicios['PERSONA_DES']."</td>
				 <td>".$row_RsServicios['MODULO_DES']."</td>
				 <td>".$row_RsServicios['SERVICIO_DES']."</td>
				 <td>".$row_RsServicios['TURNOS_PM']."</td>
				 <td>".$row_RsServicios['REALIZADOS']."</td>
				 <td><ul><li><a href=\"javascript: Ayudar(".$armarlink.");\">Ayudar</a></li></ul></td>
				</tr>
				";
				}
			 }while($row_RsServicios = mysqli_fetch_assoc($RsServicios));
			 
			 $contmult=TablamodeAyuda($turnos,'1');
		echo($table.$cont.$contmult.$endtable);
		  }		
		else{
		  $contmult=TablamodeAyuda($turnos,'1');
		    //echo ("<table  class='datagrid'><tr class=''><td colspan='6'><ul><li><a href='javascript: generarAyuda();'>Actualizar</a></li><li><a href='javascript: ModoNormal();'> Regresar a Modo normal</a></li></ul></td></tr>".$contmult."</table>");
			echo($table.$contmult.$endtable);
		  }
		
		
		}
	

  }
} 
