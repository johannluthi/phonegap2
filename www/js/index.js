var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicity call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
		
        var parentElement = document.getElementById(id);
        var listeningElement = parentElement.querySelector('.listening');
        var receivedElement = parentElement.querySelector('.received');

        listeningElement.setAttribute('style', 'display:none;');
        receivedElement.setAttribute('style', 'display:block;');

        console.log('Received Event: ' + id);
    },
};
	
// Object php API

function load(objet) {
	$('#list').show();
    $.ajax("http://www.educh.ch/api/" + objet + ".php").done(function(data) {
        var i, obj;
		$("#all").empty();
        $.each(data.items, function (i, obj) {
            $("#all").append("<li><a onclick='loadObjet(&quot;" + objet + "&quot;," + obj.id + ")'>"
            + "<h4>" + obj.title + "</h4></a></li>");
        });
        $('#all').listview('refresh');
		$('#objet').hide();
    });
}
function loadObjet(objet,id) {
	$('#objet').show();
    $.ajax("http://www.educh.ch/api/o" + objet + ".php?id=" + id + ":3000").done(function(data) {
		$("#objet").empty();
		var body = "<h2>" + data.item.title + "</h2><a href='http://www.educh.ch/" + data.item.url + "' data-role='button' data-inline='true' class='btn'>"+data.item.label_link+"</a>";
		if (data.item.img){
			body +="<img  class='img-responsive' style='margin:0 auto;' src='" + data.item.img + "'>";
		}
		body += "<p>" + data.item.texte + "</p>";
        $("#objet").append(body);
        $('#objet').listview('refresh');
    });
	$('#list').hide();
}

$('#reposHome').bind('pageinit', function(event) {
	load('ateliers');
});