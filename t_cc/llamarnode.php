<?php require_once('Connections/db.php');
      
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
		$urlnode = 'http://'.$nodeserver.':'.$nodeport;
		
		require( __DIR__ . '/elephantio/lib/ElephantIO/Client.php');
		use ElephantIO\Client as ElephantIOClient;

		$elephant = new ElephantIOClient($urlnode, 'socket.io', 1, false, true, true);
        //$turnoactual='6/MODULO 1/103/139/#ff0000/LABORATORIO ESPECIAL!';
		$elephant->init();
		if($_SESSION["SALA"]==1){
		$elephant->emit('tur_cc', $turnoactual);
		}
		if($_SESSION["SALA"]==2){
		$elephant->emit('tur_lb', $turnoactual);
		}
		$elephant->close();
		?>