<?php

require( __DIR__ . '/../lib/ElephantIO/Client.php');
use ElephantIO\Client as ElephantIOClient;

$elephant = new ElephantIOClient('http://localhost:8000', 'socket.io', 1, false, true, true);
$miturno='6/MODULO 1/103/139/#ff0000/LABORATORIO ESPECIAL!';
$elephant->init();
$elephant->emit('turnero', $miturno);
$elephant->close();

//echo 'tryin to send `foo` to the event called action';
?>