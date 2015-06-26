var io = require('socket.io')(8080);
var mysql = require('mysql');
var pool = mysql.createPool({
  host     : 'localhost',
  database : 'wordpress',
  user     : 'pharao',
  password : 'admin'
});


var chat = io
	.on(
		'connection',
		function (socket) 
		{
			socket.on('giveNewsFeed', function (data)
			{
				getNews(
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


function getNews (callback) {
	pool.getConnection(function(err, connection) {
		if (err) {
			connection.release();
			return;
		}

		connection.query( 'SELECT * FROM  `wp_dokumummy_update_feed` ORDER BY created_at DESC LIMIT 0 , 3 ', function(err, rows) {
			connection.release();
			if (err) {
				return;
			}
			
            callback(rows);
		});
	});
}