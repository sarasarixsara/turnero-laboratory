<?php // content="text/plain; charset=utf-8"
	//require_once('seguridad.php');
	require_once('Connections/db.php');
	if (!isset($_SESSION)) {
			session_start();
		}
		
	mysql_select_db($database_turnos, $turnos);
	@mysql_query("SET collation_connection = utf8_general_ci;");
	mysql_query ("SET NAMES 'utf8'");

	//$fecha_inicio='01/02/2014';
	if(isset($_GET['fecha_inicio']) && $_GET['fecha_inicio']!=''){
	$fecha_inicio=$_GET['fecha_inicio'];
	}

	//$fecha_fin='16/02/2014';
	if(isset($_GET['fecha_fin']) && $_GET['fecha_fin']!=''){
	$fecha_fin=$_GET['fecha_fin'];
	}
	$TABLE=$_SESSION["TABLE"];
	$TABLE2=$_SESSION["TABLE2"];
$totalRows_RsDatosGrafica4=0;
	if($fecha_inicio !='' && $fecha_fin !=''){
			$query_RsDatosGrafica4="SELECT  (SELECT COUNT(T2.TURNCONS)
											    FROM ".$TABLE2." T2
											  WHERE T2.TURNTURN = 2
											   AND  T2.TURNIDAP = T.TURNIDUS
											   AND  T2.TURNFECH >= STR_TO_DATE('".$fecha_inicio."','%d/%m/%Y')
									           and  T2.TURNFEFI <= STR_TO_DATE('".$fecha_fin."','%d/%m/%Y')
											   AND  T2.TURNIDES = '2'
											   AND  T2.TURNAPOY = '1') CANTIDAD,
										   T.TURNIDAP USUARIO,
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
									   AND T.TURNAPOY = 1
									 GROUP BY T.TURNIDUS
									 order by TURNIDUS
								";
								//echo($query_RsDatosGrafica4);
			$RsDatosGrafica4           = mysql_query($query_RsDatosGrafica4, $turnos) or die(mysql_error());
			$row_RsDatosGrafica4       = mysql_fetch_assoc($RsDatosGrafica4);		
			$totalRows_RsDatosGrafica4 = mysql_num_rows($RsDatosGrafica4);
	}

if($totalRows_RsDatosGrafica4>0){
$data4 = array();
$usuarios4=array();
$colors4=array('#1E90FF','#2E8B57','#ADFF2F','#BA55D3','#F2EE2E','#8C2A07','#1031ED','#CCCCCC','#FF961A','#F72116','#F79EF4','#a2d24','#DBEFB3','#6B0887','#EDAE65','#689B01','#F98884','#2F3D8E','#808BCC','#F7B6B4','#B56246','#3DF7E4');
do{
   $data4[]     = $row_RsDatosGrafica4['CANTIDAD'];
   $usuarios4[] = $row_RsDatosGrafica4['USUARIO_DES'];
  }while($row_RsDatosGrafica4       = mysql_fetch_assoc($RsDatosGrafica4));
 ?><table>
   <tr>
     <td><img src="imagen4.php?fecha_inicio=<?php echo($fecha_inicio);?>&fecha_fin=<?php echo($fecha_fin);?>" alt="" width="400" height="320"></td>
   </tr>
   <tr>
     <td>
	   <table>
	     <?php
		  for($i=0; $i<count($usuarios4); $i++){
		   ?>
		   <tr>
		     <td width="13" bgcolor="<?php echo($colors4[$i]);?>"></td>
			 <td><?php echo($usuarios4[$i]);?></td>
			 <td><?php echo('('.$data4[$i].')');?></td>
		   </tr>
		   <?php
		  }
		  ?>
		 <?php
		 
		 ?>
	   </table>
	 </td>
   </tr>
 </table><?php
}
?>