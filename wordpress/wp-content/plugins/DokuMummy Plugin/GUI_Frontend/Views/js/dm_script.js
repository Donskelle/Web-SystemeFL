function init() {
	var that = this;
	var test = document.querySelectorAll(".abschnitt a");


	for (var i = 0; i < test.length; i++) {
		test[i].addEventListener("click", function(e){
			document.getElementById("lightboxIFrame").src = e.target.href;
			that.showLightbox();
			e.preventDefault();
			return false;
		});
	};

	

	this.initLightbox = function() {
		var that = this;
		var lightboxWrapper = document.createElement("div");
		lightboxWrapper.className = "lightboxWrapper";

		var lightboxContent = document.createElement("div");
		var lightboxFrame = document.createElement("iframe");
		lightboxContent.id = "lightboxContent";
		lightboxFrame.id = "lightboxIFrame";

		lightboxContent.appendChild(lightboxFrame);
		lightboxWrapper.appendChild(lightboxContent);
		lightboxWrapper.addEventListener("click", function(e) {
			if(e.target.className == "lightboxWrapper")
			{
				that.hideLightbox();
			}
		});

		document.body.appendChild(lightboxWrapper);
	}

	this.showLightbox = function(href){
		document.querySelector(".lightboxWrapper").style.display = "block";
	}

	this.hideLightbox = function(){
		document.querySelector(".lightboxWrapper").style.display = "none";
	}

	this.initLightbox();
}

window.addEventListener('DOMContentLoaded', init, false);