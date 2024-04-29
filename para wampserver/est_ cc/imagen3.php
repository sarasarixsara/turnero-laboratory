<?php // content="text/plain; charset=utf-8"


// Create the Pie Graph. 

	//require_once('seguridad.php');
	require_once('Connections/db.php');
	if (!isset($_SESSION)) {
			session_start();
		}
		
	mysql_select_db($database_turnos, $turnos);
	@mysql_query("SET collation_connection = utf8_general_ci;");
	mysql_query ("SET NAMES 'utf8'");

	$fecha_inicio='01/02/2014';
	if(isset($_GET['fecha_inicio']) && $_GET['fecha_inicio']!=''){
	$fecha_inicio=$_GET['fecha_inicio'];
	}

	$fecha_fin='16/02/2014';
	if(isset($_GET['fecha_fin']) && $_GET['fecha_fin']!=''){
	$fecha_fin=$_GET['fecha_fin'];
	}
	$TABLE=$_SESSION["TABLE"];
	$TABLE2=$_SESSION["TABLE2"];

	if($fecha_inicio !='' && $fecha_fin !=''){
			$query_RsDatosGrafica2="SELECT  COUNT(T.TURNCONS) CANTIDAD,
										   T.TURNIDUS USUARIO,
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
									   AND T.TURNTURN = 2
									 GROUP BY T.TURNIDUS
									 order by TURNIDUS
								";
								//echo($query_RsDatosGrafica2);
			$RsDatosGrafica2           = mysql_query($query_RsDatosGrafica2, $turnos) or die(mysql_error());
			$row_RsDatosGrafica2       = mysql_fetch_assoc($RsDatosGrafica2);		
			$totalRows_RsDatosGrafica2 = mysql_num_rows($RsDatosGrafica2);
	}

if($totalRows_RsDatosGrafica2>0){
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');
require_once ('jpgraph/jpgraph_pie3d.php');
$data2 = array();
do{
   $data2[]=$row_RsDatosGrafica2['CANTIDAD'];
  }while($row_RsDatosGrafica2       = mysql_fetch_assoc($RsDatosGrafica2));
$graph2 = new PieGraph(530,440);
$graph2->SetShadow();


$theme_class= new UniversalTheme;
$graph2->SetTheme($theme_class);

// Set A title for the plot
//$graph2->title->Set("grafica total turnos tarde");
$graph2->legend->Pos(0.1,0.9);
// Create
$p2 = new PiePlot3D($data2);
$p2->SetTheme("sand");
$graph2->Add($p2);

$p2->ShowBorder();
$p2->SetColor('black');
$p2->SetSliceColors(array('#1E90FF','#2E8B57','#ADFF2F','#BA55D3','#F2EE2E','#8C2A07','#1031ED','#CCCCCC','#FF961A','#F72116','#F79EF4','#a2d24','#DBEFB3','#6B0887','#EDAE65','#689B01','#F98884','#2F3D8E','#808BCC','#F7B6B4','#B56246','#3DF7E4'));
//$p1->SetLegends(array("Jan (%d)","Feb","Mar","Apr","mayo","junio","julio","agosto","sept","octu","nov",'dic','masuno'));
// Adjust projection angle
$p2->SetAngle(45);

// Adjsut angle for first slice
$p2->SetStartAngle(45);
$p2->ExplodeSlice(1);
$p2->value->SetFormat('%2.1f%%');
$graph2->Stroke();
}

?>