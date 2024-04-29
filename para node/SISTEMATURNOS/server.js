/*var io = require('socket.io').listen(8000);

io.sockets.on('connection', function (socket) {
  console.log('user connected');

  socket.on('action', function (data) {
    console.log('here we are in action event and data is: ' + data);
  });
});
*/
var mysql = require('mysql');
var io = require('socket.io');
var socket = io.listen(8000);
var usuariosOnline = {};
socket.on('connection', function(client) {
    client.join('salaTurnos');

    client.on('message', function(event) {
        console.log('Mensaje recibido del cliente! ', event);
        client.send(event);
    });

    client.on('tur_cc', function(data) {
        client.broadcast.in('salaTurnos').emit('PANTALLATV_CC', data);
        client.in('salaTurnos').emit('PANTALLATV_CC', data);
    });
    client.on('tur_lb', function(data) {
        client.broadcast.in('salaTurnos').emit('PANTALLATV_LB', data);
        client.in('salaTurnos').emit('PANTALLATV_LB', data);
    });

    client.on('disconnect', function() {
        //console.log('Un usuario ha salido de el sistema de turnos ');
				//si el usuario, por ejemplo, sin estar logueado refresca la
		//página, el typeof del socket username es undefined, y el mensaje sería 
		//El usuario undefined se ha desconectado del turnero, con ésto lo evitamos
		if(typeof(client.userid) == "undefined")
		{
		    console.log('upd page user no logueado en el ws');
			return;
		}
				//console.log("aaaalina "+usuariosOnline[client.userid]);
		//en otro caso, eliminamos al usuario
		ultimo = usuariosOnline[client.userid];
		delete usuariosOnline[client.userid];
		console.log('Un usuario ha salido de el sistema de turnos '+client.userid);
		client.broadcast.emit("MostrarUsuarios", usuariosOnline);
		datos = ultimo.split('|');
		
		//begin
		if(datos[4]==1){
		
			var connection = mysql.createConnection({
				  host     : 'localhost',
				  user     : 'root',
				  password : '',
				  database : 'tur_cc'
				});

				connection.connect();

				  connection.query("UPDATE MODULOS SET MODUESTA=0, MODUUSUA='',MODUSERV='', MODUMULT='' WHERE MODUUSUA = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("reset modulos correctamente");
					}
				  
				  });

				  connection.query("UPDATE USUARIOS SET USUAESTA = 0 WHERE USUAID = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("reset usuario correctamente");
					}
				  
				  });
				  
				  connection.end();
				  
			 //end
			 }
		//begin
		if(datos[4]==2){
		
			var connection = mysql.createConnection({
				  host     : 'localhost',
				  user     : 'root',
				  password : '',
				  database : 'tur_lb'
				});

				connection.connect();

				  connection.query("UPDATE MODULOS SET MODUESTA=0, MODUUSUA='',MODUSERV='', MODUMULT='' WHERE MODUUSUA = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("reset modulos correctamente");
					}
				  
				  });

				  connection.query("UPDATE USUARIOS SET USUAESTA = 0 WHERE USUAID = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("reset usuario correctamente");
					}
				  
				  });
				  
				  connection.end();
				  
			 //end
			 }			 
			  
			});
	
	client.on("UsuariosLogueados", function(userid)
	{
	 datos=userid.split('|');
	 userid=datos[0];
	  console.log('usuario conectado de codigo: '+userid);
	  //datos del split 
	  //datos[0] = codigo de usuario
	  //datos[1] = nombre de la persona
	  //datos[2] = codigo del modulo
	  //datos[3] = nombre modulo
	  //datos[4] = codigo del servicio si es unico solo un numero si es multiple codigos separados por coma 1,2,4
	  //datos[5] = tipo de sala
	  //datos[6] = servicio unico (0) o multiple (1)
		//si existe el nombre de usuario en el turnero
		if(usuariosOnline[userid])
		{
			client.emit("userInUse");
			//console.log('El usuario esta repetido');
			return;
		}
		//Guardamos el nombre de usuario en la sesión del socket para este cliente
		client.userid = userid;
		//añadimos al usuario a la lista global donde almacenamos usuarios
		//usuariosOnline[userid] = client.userid;
		usuariosOnline[userid] = datos[0]+'|'+datos[1]+'|'+datos[2]+'|'+datos[3]+'|'+datos[5];
		
		//mostramos al cliente como que se ha conectado
		//socket.emit("refreshturnero", "yo", "Bienvenido " + socket.userid + ", te has conectado correctamente.");
		//mostramos de forma global a todos los usuarios que un usuario
		//se acaba de conectar al turnero
		//socket.broadcast.emit("refreshturnero", "conectado", "El usuario " + socket.userid + " se ha conectado al turnero.");
		//actualizamos la lista de usuarios en el turnero del lado del cliente
		//io.sockets.emit("updateSidebarUsers", usuariosOnline);
		client.broadcast.emit("MostrarUsuarios",usuariosOnline);
		client.emit("MostrarUsuarios", usuariosOnline);
		
		if(datos[5]==1){ //la sala es 1 osea central de citas piso 2
		
			var connection = mysql.createConnection({
				  host     : 'localhost',
				  user     : 'root',
				  password : '',
				  database : 'tur_cc'
				});

				connection.connect();

				  connection.query("UPDATE MODULOS SET MODUESTA=1, MODUUSUA='"+client.userid+"', MODUSERV='"+datos[4]+"', MODUMULT='"+datos[5]+"' WHERE MODUID = "+datos[2]+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("upd modulos por accion navegador");
					}
				  
				  });
				  connection.query("UPDATE USUARIOS SET USUAESTA=1 WHERE USUAID = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("upd usuarios por accion navegador");
					}
				  
				  });			  
				  connection.end();
			  }
			  
		if(datos[5]==2){ //la sala es 2 osea piso 1 laboratorios
		
			var connection = mysql.createConnection({
				  host     : 'localhost',
				  user     : 'root',
				  password : '',
				  database : 'tur_lb'
				});

				connection.connect();

				  connection.query("UPDATE MODULOS SET MODUESTA=1, MODUUSUA='"+client.userid+"', MODUSERV='"+datos[4]+"', MODUMULT='"+datos[5]+"' WHERE MODUID = "+datos[2]+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("upd modulos por accion navegador");
					}
				  
				  });
				  connection.query("UPDATE USUARIOS SET USUAESTA=1 WHERE USUAID = "+client.userid+"", function (err, results, fields) {
					if (err) {
					console.log("Error: " + err.message);
					throw err;
					} else {
							console.log("upd usuarios por accion navegador");
					}
				  
				  });			  
				  connection.end();
			  }			  
		
	});	
});