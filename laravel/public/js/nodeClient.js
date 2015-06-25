$(function () {
    /* For demo purposes */
    var demo = $("<div />").css({
        position: "fixed",
        top: "70px",
        right: "0",
        background: "#fff",
        "border-radius": "5px 0px 0px 5px",
        padding: "10px 15px",
        "font-size": "16px",
        "z-index": "99999",
        cursor: "pointer",
        color: "#3c8dbc",
        "box-shadow": "0 1px 3px rgba(0,0,0,0.1)"
    }).html("<i class='fa fa-gear'></i>").addClass("no-print");

    var demo_settings = $("<div />").css({
        "padding": "10px",
        position: "fixed",
        top: "70px",
        right: "-250px",
        background: "#fff",
        border: "0px solid #ddd",
        "width": "250px",
        "z-index": "99999",
        "box-shadow": "0 1px 3px rgba(0,0,0,0.1)"
    }).addClass("no-print");
    demo_settings.append(
            "<h4 class='text-light-blue' style='margin: 0 0 5px 0; border-bottom: 1px solid #ddd; padding-bottom: 15px;'>Chat und News Feed</h4>"
            //Fixed layout
            + "<div class='form-group'>"
            + "<div class='checkbox'>"
            + "<label>"
            + "<input type='checkbox' onchange='change_layout(\"fixed\");'/> "
            + "Fixed layout"
            + "</label>"
            + "</div>"
            + "</div>"
    
            //Sidebar Collapse
            + "<div class='newsFeed'>"
            + "</div>"
            );

    demo.click(function () {
        if (!$(this).hasClass("open")) {
            $(this).animate({"right": "250px"});
            demo_settings.animate({"right": "0"});
            $(this).addClass("open");
        } else {
            $(this).animate({"right": "0"});
            demo_settings.animate({"right": "-250px"});
            $(this).removeClass("open");
        }
    });

    $("body").append(demo);
    $("body").append(demo_settings);
});




/**
 * [UpdateFeed description]
 * Namesbereich für Methoden zur Darstellung
 */
function UpdateFeed() {

}
(function () {
    /**
     * [buildNews description]
     * @param  {[type]} data [description]
     * @return {[type]}      [description]
     */
    this.buildNews = function (data) {
        var newsContainer = document.querySelector(".newsFeed");
        newsContainer.innerHTML = "";

        for (var i = 0; i < data.length; i++) {
            UpdateFeed.addNews(data[i].name);
        }
        ;
    }

    /**
     * [addNews description]
     * @param {[type]} data [description]
     */
    this.addNews = function (data) {
        var newsContainer = document.querySelector(".newsFeed");
        newsContainer.innerHTML = "<p>" + data + "</p>";
    }    
}).call(UpdateFeed);
