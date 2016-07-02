// Eventify, Responsive HTML5 Event Template - Version 1.1 //

// Javascripts //
$(document).ready(function () {
	
	// Top Bar //
	$('.top-bar nav').addClass('hidden');
	$('.menu-link').on('click', function (
		e) {
		e.preventDefault();
		$('.top-bar nav').toggleClass(
			'hidden');
	});
	$(window).scroll(function () {
		if ($(window).scrollTop() <= 50) {
			$('.top-bar').removeClass('alt')
		} else {
			$('.top-bar').addClass('alt')
		}
	});
	$(window).load(function () {
		if ($(window).scrollTop() <= 30) {
			$('.top-bar').removeClass('alt')
		} else {
			$('.top-bar').addClass('alt')
		}
	});
	//
	$('#mainnav .nav a').click(function (e) {
		e.preventDefault();
		var des = $(this).attr('href');
		if ($('.navbar').hasClass(
			'in')) {
			$('.navbar .btn-navbar').trigger(
				'click');
		}
		goToSectionID(des);
	})
	
	// Local Scroll //
	$('#mainnav li').localScroll({
		duration: 1000
	});
	$('.logo').localScroll({
		duration: 1000
	});
	
	// One Page Nav //
	$('.top-bar').onePageNav({
		currentClass: 'current',
		filter: ':not(.external)'
	});
	
	// Calculate the viewport height //
	var viewHeight = $(window).height();
	$("#intro").css({
		'height': viewHeight
	});
	$(window).on('resize', function () {
		var viewHeight = $(window).height();
		$("#intro").css({
			'height': viewHeight
		});
	});
	
	// Flexslider
	// Can also be used with $(document).ready()
	$('.flexslider').flexslider({
		animation: "slide"
	});
	
	// Tabs //
	$('#schedule-tabs a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	})
	
	// Tooltip //
	$("[rel=tooltip]").tooltip();
	$("[data-rel=tooltip]").tooltip();
	
	//.parallax(xPosition, speedFactor, outerHeight) options:
	//xPosition - Horizontal position of the element
	//inertia - speed to move relative to vertical scroll. Example: 0.1 is one tenth the speed of scrolling, 2 is twice the speed of scrolling
	//outerHeight (true/false) - Whether or not jQuery should use it's outerHeight option to determine when a section is in the viewport
	$('#intro').parallax("50%", 0.1);
	$('#venue').parallax("50%", 0.02);
	
	// Carousel //
	$(".speakers-carousel").carousel({
		dispItems: 1,
		direction: "horizontal",
		pagination: false,
		loop: false,
		autoSlide: false,
		autoSlideInterval: 5000,
		delayAutoSlide: 2000,
		effect: "slide",
		animSpeed: "slow"
	});
	
	// Toggle //
	$('.toggle-item-title').click(function () {
		$(this).next().slideToggle();
		$(this).toggleClass(
			'ui-state-active');
	});
	
	// Countdown //
	$('#countdown').countdown({
		until: new Date(2014, 11 - 1, 29), // new Date(year, mth - 1, day, hr, min, sec) - date/time to count down to 
		// or numeric for seconds offset, or string for unit offset(s): 
		// 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds 
		// until: '-1m +1d', for demo
		//************Coloquei -2, porque em Novembro vai estar rolando o horário de verão!
		timezone: -2, // The timezone (hours or minutes from GMT) for the target times, or null for client local 
		layout: '{d<}<div class="span3"><div class="digit-container">{dn}<span class="label-container">{dl}</span></div></div>{d>}{h<}<div class="span3"><div class="digit-container">{hn}<span class="label-container">{hl}</span></div></div>{h>}{m<}<div class="span3"><div class="digit-container">{mn}<span class="label-container">{ml}</span></div></div>{m>}{s<}<div class="span3"><div class="digit-container">{sn}<span class="label-container">{sl}</span></div></div>{s>}',
		timeSeparator: '', // Separator for time periods 
		isRTL: false, // True for right-to-left languages, false for left-to-right 
		format: 'dHMS', // Format for display - upper case for always, lower case only if non-zero,
		// 'Y' years, 'O' months, 'W' weeks, 'D' days, 'H' hours, 'M' minutes, 'S' seconds
		alwaysExpire: true, // True to trigger onExpiry even if never counted down 
		onExpiry: liftOff // Callback when the countdown expires - 
		// receives no parameters and 'this' is the containing division 
	});
	// Functions if countdown timer runs out:
	function liftOff() {
		$('.hasCountdown').css({
			display: 'none'
		});
		$('#countdown').addClass('hidden');
		$('#register-button').addClass('hidden');
		$('.register-title').addClass('hidden');
		$('.register-box').append('<h2>We are at capacity and can no longer accept registrations.</h2>');
		$('.register-box').append('<button class="btn btn-large btn-primary disabled" disabled="true" id="register-button">Sold Out</button>');
	}
	
	// Twitter Feed //
	/*$('.tweet').twittie({
		dateFormat: '%B %d, %Y',
		template: '<div class="date">{{date}}</div> {{tweet}}',
		count: 3, // define the number of tweets to be displayed, if it's one, read the lines below
		hideReplies: true
	});
	// if you want to display only one tweet, please remove the following lines:
	// if so, don't forget you need to change style.css line 1036 display property to display: block;
	setInterval(function () {
		var item = $('.tweet ul').find('li:first');
		item.animate({
			'opacity': '0'
		}, 1000, function () {
			$(this).detach().appendTo('.tweet ul').removeAttr('style');
		});
	}, 12000);*/
	
	// Contact Form //
	$('#contactform').validationEngine();
    
    // send the form by ajax when sumbitted
    $('#contactform').submit(function(e){
        e.preventDefault();
        var submitUrl = $(this).attr('action');
        $.ajax({
            url: submitUrl,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'text',
            beforeSend: function () {
                $('#submit').attr('disabled', 'disabled');
                $('#ErrorMsgs').fadeOut('slow').html('<div class="alert alert-info">Enviando mensagem...<a href="#" class="close">&times;</a></div>').fadeIn('slow');
            },
            success: function(data) {
            	if (data === '0') {
            		//$('#contactform')[0].reset();
            		$('#ErrorMsgs').fadeOut('slow').html('<div class="alert alert-info">Mensagem enviada com sucesso! :)</div>').fadeIn('slow');
				} else {
            		$('#ErrorMsgs').fadeOut('slow').html('<div class="alert alert-error">' + data + '</div>').fadeIn('slow');
            	}
                $('#submit').removeAttr('disabled');
            },
        	error: function (data) {
        		$('#ErrorMsgs').fadeOut('slow').html('<div class="alert alert-error">Erro durante o envio da mensagem :(<br/>Por favor, tente novamente mais tarde...</div>').fadeIn('slow');
        		$('#submit').removeAttr('disabled');
        	}
		});
        return false;
    });

	// frmEnrollment //
    $('#frmEnrollment').validationEngine();

	// send the form by ajax when sumbitted
    $('#frmEnrollment').submit(function (e) {
    	e.preventDefault();
    	var submitUrl = $(this).attr('action');
    	$.ajax({
    		url: submitUrl,
    		type: 'POST',
    		data: $(this).serialize(),
    		dataType: 'text',
    		beforeSend: function () {
    			$('#btnSend').attr('disabled', 'disabled');
    			$('#p-fb').fadeOut('slow').html('<div class="alert alert-info">Realizando inscrição...</div>').fadeIn('slow');
    		},
    		success: function (data) {
    			if (data === '0') {
    				var f = document.getElementById('frmEnrollment');
    				f.parentNode.removeChild(f);
    				$('#p-fb').fadeOut('slow').html('Inscrição realizada com sucesso! :)').fadeIn('slow');
    			} else {
    				$('#p-fb').fadeOut('slow').html(data).fadeIn('slow');
    				$('#btnSend').removeAttr('disabled');
				}
    		},
    		error: function (data) {
    			$('#p-fb').fadeOut('slow').html('<div class="alert alert-error">Erro durante a inscrição :(<br/>Por favor, tente novamente mais tarde...</div>').fadeIn('slow');
    			$('#btnSend').removeAttr('disabled');
    		}
    	});
    	return false;
    });


	// Google Map //
	$('#map_canvas').gmap({
		'center': new google.maps.LatLng(-23.5778533, -46.6434618), // Change this to your desired latitude and longitude
		'zoom': 17,
		'mapTypeControl': false,
		'navigationControl': false,
		'streetViewControl': false,
		'styles': [{
			stylers: [{
				gamma: 0.60
			}, {
				hue: "#5DBEB2"
			}, {
				invert_lightness: false
			}, {
				lightness: 2
			}, {
				saturation: -20
			}, {
				visibility: "on"
			}]
		}]
	});
	var image = {
		url: 'images/marker.png', // Define the map marker file here
		// This marker is 51 pixels wide by 63 pixels tall.
		size: new google.maps.Size(51, 63),
		// The origin for this image is 0,0.
		origin: new google.maps.Point(0, 0),
		// The anchor for this image is the base of the flagpole at 26,63.
		anchor: new google.maps.Point(26, 63)
	};
	$('#map_canvas').gmap().bind('init', function () {
		$('#map_canvas').gmap('addMarker', {
			'id': 'marker-1',
			'position': '-23.5778533,-46.6434618',
			'bounds': false,
			'icon': image
		}).click(function () {
			$('#map_canvas').gmap('openInfoWindow', {
				'content': '<h4>InteropMIX</h4><p><strong>Faculdade BandTec</strong><br>Rua Estela, 268, São Paulo, SP</p>'
			}, this);
		});
	});
	
	// end		
})