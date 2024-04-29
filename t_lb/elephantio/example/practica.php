<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PANTALLA TV</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="jquery/jquery1.10.2.min.js"></script>
        <script src="socket/socket.io.min.js"></script>
        <script>
            var socket;
            $(document).ready(function() {
                socket = new io.connect('localhost', {
                    port: 8000
                });
            
                socket.on('connect', function() {
                    console.log('Client has connected to the server!');
                });

                socket.on('message', function(data) {
                    $('#chat').prepend('<div>' + data.usu + ': ' + data.msg + '</div>');
                });

                socket.on('PANTALLATV', function(data) {
                    //$('#chat').append('<div>' + data.usu + ': ' + data.msg + '</div>');
                    $('#chat').append('<div>' + data + '</div>');
                });

                socket.on('disconnect', function() {
                    console.log('The client has disconnected!');
                    $('#chat').html('');
                });
            });

            function sendMessageToServer() {
                var message = $('#mensaje');
                var usuario = $('#nick');
                socket.emit('turnero', {usu: usuario.val(), msg: message.val()});
                message.val('').focus();
            }
        </script>
    </head>
    <body>
        <div style="width: 300px">
            <div id="chat" style="height: 100%"></div>
            <div>
                <input type="text" id="nick" />
                <br />
                <input type="text" id="mensaje" /><input type="button" id="btnEnviar" value="Enviar" onclick="sendMessageToServer()" />
            </div>
        </div>
    </body>
</html>