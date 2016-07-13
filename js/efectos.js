$(function(){
	// Activa el fading de las imagenes del titulo
	$('#previa img').animate({
        "opacity" : .5  
      });

	$('.carousel').carousel({
        interval: 4000
    });

	$('.carousel').carousel('cycle');

	$('#dateField').datepicker();

	$('video').bind('contextmenu',function() { return false; });

	$("#myModal").on('hide.bs.modal', function(evt){
	    var player = $(evt.target).find('video');
	    player.prop('src', ''); // to force it to pause
	  });
	
	$('#previa img').hover(function(){
		$(this).stop().animate({ "opacity" : 1 });
	}, function(){
		$(this).stop().animate({ "opacity" : .5});
	});
});


