/*global jQuery:false */
(function ($) {


	$(window).load(function(){
      $("#navigation").sticky({ topSpacing: 0 });
    });
	
	
	//jQuery to collapse the navbar on scroll
	$(window).scroll(function() {
		if ($(".navbar").offset().top > 50) {
			$(".navbar-fixed-top").addClass("top-nav-collapse");
		} else {
			$(".navbar-fixed-top").removeClass("top-nav-collapse");
		}
	});

	$(".navbar-collapse li.active a").on('click', function() {
    $(".navbar-collapse.collapse").removeClass('in');
  });

	
	//jQuery for page scrolling feature - requires jQuery Easing plugin
	$(function() {
		$('.navbar-nav li a').bind('click', function(event) {
			var $anchor = $(this);
			var nav = $($anchor.attr('href'));
			if (nav.length) {
			$('html, body').stop().animate({				
				scrollTop: $($anchor.attr('href')).offset().top				
			}, 1500, 'easeInOutExpo');
			
			event.preventDefault();
			}
		});
		$('a.totop,a#btn-scroll,a.btn-scroll,.carousel-inner .item a.btn').bind('click', function(event) {
			var $anchor = $(this);
			$('html, body').stop().animate({
				scrollTop: $($anchor.attr('href')).offset().top
			}, 1500, 'easeInOutExpo');
			event.preventDefault();
		});
	});

			//scroll to top
			$(window).scroll(function(){
				if ($(this).scrollTop() > 1000) {
					$('.scrollup').fadeIn();
					} else {
					$('.scrollup').fadeOut();
				}
			});
			$('.scrollup').click(function(){
				$("html, body").animate({ scrollTop: 0 }, 1000);
					return false;
			});

	
	//nivo lightbox
	$('.gallery-item a').nivoLightbox({
		effect: 'fadeScale',                             // The effect to use when showing the lightbox
		theme: 'default',                           // The lightbox theme to use
		keyboardNav: true,                          // Enable/Disable keyboard navigation (left/right/escape)
		clickOverlayToClose: true,                  // If false clicking the "close" button will be the only way to close the lightbox
		onInit: function(){},                       // Callback when lightbox has loaded
		beforeShowLightbox: function(){},           // Callback before the lightbox is shown
		afterShowLightbox: function(lightbox){},    // Callback after the lightbox is shown
		beforeHideLightbox: function(){},           // Callback before the lightbox is hidden
		afterHideLightbox: function(){},            // Callback after the lightbox is hidden
		onPrev: function(element){},                // Callback when the lightbox gallery goes to previous item
		onNext: function(element){},                // Callback when the lightbox gallery goes to next item
		errorMessage: 'The requested content cannot be loaded. Please try again later.' // Error message when content can't be loaded
	});

	jQuery('.appear').appear();
	jQuery(".appear").on("appear", function(data) {
			var id = $(this).attr("id");
			jQuery('.nav li').removeClass('active');
			jQuery(".nav a[href='#" + id + "']").parent().addClass("active");					
		});


		//accordion
		$(function() {
			// (Optional) Active an item if it has the class "is-active"	
			$(".accordion > .accordion-item.is-active").children(".accordion-panel").slideDown();
			
			$(".accordion > .accordion-item").click(function() {
				// Cancel the siblings
				$(this).siblings(".accordion-item").removeClass("is-active").children(".accordion-panel").slideUp();
				// Toggle the item
				$(this).toggleClass("is-active").children(".accordion-panel").slideToggle("ease-out");
			});
		});
	
})(jQuery);
