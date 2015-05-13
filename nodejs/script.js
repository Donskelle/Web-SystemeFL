(function init() {
	var news = io.connect('http://192.168.2.111:8080');
	var username = "Fabian";

	news.on('connect', function () 
	{
		news.emit(
			'init', 
			{
				rooms: ["lufthansa", "airbus"],
				name: username
			}
		);

		/**
		 * Kompletter Newsfeed wird erstetzt
		 * Wird ausgelöst, wenn man Räumen begetreten ist.
		 * NodeJs ruft DatenBank Newsfeed ab.
		 */
		news.on( 
			'newsFeed', 
			function (msg) 
			{
				UpdateFeed.buildNews(msg.rows);
			}
		);


		/**
		 * Server schickt Update an Client
		 */
		news.on(
			'update',
			function(data) {
				UpdateFeed.addNews(data);
			}
		);

	});

	$("#update").on(
		"click", 
		function(e)
		{
			// Client löst Update aus
			news.emit(
				'update', 
				{
					room: e.target.attributes["data-room"].value,
					updateNews: $("#" + e.target.attributes["data-targetInput"].value)[0].value
				}
			);
		}
	);
})();


/**
 * [UpdateFeed description]
 * Namesbereich für Methoden zur Darstellung
 */
function UpdateFeed () {

}
(function(){
	/**
	 * [buildNews description]
	 * @param  {[type]} data [description]
	 * @return {[type]}      [description]
	 */
	this.buildNews = function(data) {
		var newsContainer = document.querySelector(".newsFeed");
		newsContainer.innerHTML = "";

		for (var i = 0; i < data.length; i++) {
			UpdateFeed.addNews(data[i].name);
		};
	}

	/**
	 * [addNews description]
	 * @param {[type]} data [description]
	 */
	this.addNews = function(data) {
		var newsContainer = document.querySelector(".newsFeed");
		newsContainer.innerHTML = newsContainer.innerHTML +  "<p>" + data + "</p>";
	}
}).call(UpdateFeed);