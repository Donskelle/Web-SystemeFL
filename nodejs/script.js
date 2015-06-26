function newsBuilder() {
	var socket;


	function init() {
		socket = io.connect('http://192.168.56.101:8080');

		socket.on('connect', function () 
		{
			
			getNewsfeed();
			/**
			 * Kompletter Newsfeed wird erstetzt
			 * Wird ausgelöst, wenn man Räumen begetreten ist.
			 * NodeJs ruft DatenBank Newsfeed ab.
			 */
			socket.on( 
				'newsFeed',  
				function (msg) {
					console.log(msg.rows);
					buildNews(msg.rows);
				}
			);
		});
	}
	init();

	function getNewsfeed() {
		socket.emit(
			'giveNewsFeed'
		);

		var timer = window.setTimeout(getNewsfeed, 10000);
	}

	function buildNews(data) {
		var newsContainer = document.querySelector(".newsFeed");
		newsContainer.innerHTML = "";

		for (var i = 0; i < data.length; i++) {
			addNews(data[i].name);
		};
	}
	function addNews(data) {
		var newsContainer = document.querySelector(".newsFeed");
		newsContainer.innerHTML = newsContainer.innerHTML +  "<p>" + data + "</p>";
	}
}
newsBuilder();