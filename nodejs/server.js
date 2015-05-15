var io = require('socket.io')(8080);
var mysql = require('mysql');
var pool = mysql.createPool({
  host     : 'localhost',
  database : 'mysql',
  user     : 'pharao',
  password : 'admin'
});


var chat = io
	.on(
		'connection',
		function (socket) 
		{
			var username;

			socket.on('update', function (data)
			{
				io.sockets.in(data.room).emit("update", "Raum " + data.room + " :" + data.updateNews);
			});

			socket.on('init', function (data)
			{
				username = data.name;
				
				for (var i = 0; i < data.rooms.length; i++) {
					socket.join(data.rooms[i]);
					// An alle außer Socket Instanz
					io.sockets.in(data.rooms[i]).emit("update", "Raum " + data.rooms[i] + ": Neuer User " + username);
					// An alle Clients im Raum
					//socket.broadcast.to(data.room[i]).emit('update', "Raum " + data.rooms[i] + ": Neuer User " + username)
				};
				console.log(socket.rooms);

				getRoomNews(
					data.rooms,
					function(databaseRows)
					{
						socket.emit(
							"newsFeed",
							{
								rows: databaseRows
							}
						);
					}
				);
			});
		}
	);


function getRoomNews (rooms, callback) {
	pool.getConnection(function(err, connection) {
		if (err) {
			connection.release();
			return;
		}

		connection.query( 'SELECT * FROM  `help_category` ', function(err, rows) {
			connection.release();
			if (err) {
				return;
			}
			
            callback(rows);
		});
	});
}