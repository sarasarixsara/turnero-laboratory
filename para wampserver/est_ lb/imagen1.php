<?php // content="text/plain; charset=utf-8"


// Some data


// Create the Pie Graph. 

	//require_once('seguridad.php');
	require_once('Connections/db.php');
	if (!isset($_SESSION)) {
			session_start();
		}
		
	mysql_select_db($database_turnos, $turnos);
	@mysql_query("SET collation_connection = utf8_general_ci;");
	mysql_query ("SET NAMES 'utf8'");

	//$fecha_inicio='01/02/2014';
	$fecha_inicio='';
	if(isset($_GET['fecha_inicio']) && $_GET['fecha_inicio']!=''){
	$fecha_inicio=$_GET['fecha_inicio'];
	}

	//$fecha_fin='16/02/2014';
	$fecha_fin='';
	if(isset($_GET['fecha_fin']) && $_GET['fecha_fin']!=''){
	$fecha_fin=$_GET['fecha_fin'];
	}
	$TABLE=$_SESSION["TABLE"];
	$TABLE2=$_SESSION["TABLE2"];

	if($fecha_inicio !='' && $fecha_fin !=''){
			$query_RsDatosGrafica="SELECT  COUNT(T.TURNCONS) CANTIDAD,
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
									 GROUP BY T.TURNIDUS
									 order by TURNIDUS
								";
								//echo($query_RsDatosGrafica);
			$RsDatosGrafica           = mysql_query($query_RsDatosGrafica, $turnos) or die(mysql_error());
			$row_RsDatosGrafica       = mysql_fetch_assoc($RsDatosGrafica);		
			$totalRows_RsDatosGrafica = mysql_num_rows($RsDatosGrafica);
	}

if($totalRows_RsDatosGrafica>0){
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_pie.php');
require_once ('jpgraph/jpgraph_pie3d.php');
$data = array();
do{
   $data[]=$row_RsDatosGrafica['CANTIDAD'];
  }while($row_RsDatosGrafica       = mysql_fetch_assoc($RsDatosGrafica));
$graph1 = new PieGraph(530,440);
$graph1->SetShadow();


$theme_class= new UniversalTheme;
$graph1->SetTheme($theme_class);

// Set A title for the plot
//$graph1->title->Set("grafica total turnos por usuario");
$graph1->legend->Pos(0.1,0.9);
// Create
$p1 = new PiePlot3D($data);
$p1->SetTheme("sand");
$graph1->Add($p1);

$p1->ShowBorder();
$p1->SetColor('black');
$p1->SetSliceColors(array('#1E90FF','#2E8B57','#ADFF2F','#BA55D3','#F2EE2E','#8C2A07','#1031ED','#CCCCCC','#FF961A','#F72116','#F79EF4','#a2d24','#DBEFB3','#6B0887','#EDAE65','#689B01','#F98884','#2F3D8E','#808BCC','#F7B6B4','#B56246','#3DF7E4'));
//$p1->SetLegends(array("Jan (%d)","Feb","Mar","Apr","mayo","junio","julio","agosto","sept","octu","nov",'dic','masuno'));
// Adjust projection angle
$p1->SetAngle(45);

// Adjsut angle for first slice
$p1->SetStartAngle(45);
$p1->ExplodeSlice(1);
$p1->value->SetFormat('%2.1f%%');
$graph1->Stroke();
}

?>