(function($) {
	'use strict'
    
	let owl = document.getElementByClassName("owl-carousel-icons");
	owl.owlCarousel({
		margin: 25,
		loop: true,
		nav: true,
		autoplay: true,
		dots: false,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1300: {
				items: 3
			}
		}
	})
	
	
	let owl = document.getElementByClassName("owl-carousel-icons2");
	owl.owlCarousel({
		loop: true,
		rewind: false,
		margin: 25,
		animateIn: 'fadeInDowm',
		animateOut: 'fadeOutDown',
		autoplay: false,
		autoplayTimeout: 5000, 
		autoplayHoverPause: true,
		dots: false,
		nav: true,
		autoplay: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 2,
				nav: true
			},
			600: {
				items: 2,
				nav: true
			},
			1300: {
				items: 4,
				nav: true
			}
		}
	})
	
	
	let owl = document.getElementByClassName("owl-carousel-icons3");
	owl.owlCarousel({
		margin: 25,
		loop: true,
		nav: false,
		dots: false,
		autoplay: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 2
			}
		}
	})
	
	
	let owl = document.getElementByClassName("owl-carousel-icons4");
	owl.owlCarousel({
		margin: 25,
		loop: true,
		nav: false,
		dots: false,
		autoplay: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 3
			},
			1000: {
				items: 6
			}
		}
	})
	
	
	let owl = document.getElementByClassName("owl-carousel-icons5");
	owl.owlCarousel({
		loop: true,
		rewind: false,
		margin: 25,
		animateIn: 'fadeInDowm',
		animateOut: 'fadeOutDown',
		autoplay: false,
		autoplayTimeout: 5000, 
		autoplayHoverPause: true,
		dots: true,
		nav: false,
		autoplay: true,
		responsiveClass: true,
		responsive: {
			0: {
				items: 1,
				nav: true
			},
			600: {
				items: 2,
				nav: true
			},
			1300: {
				items: 4,
				nav: true
			}
		}
	})
	
	
	let owl = document.getElementByClassName("owl-carousel-icons6");
	owl.owlCarousel({
		margin: 25,
		loop: true,
		nav: false,
		dots: false,
		autoplay: true,
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 3
			}
		}
	})
	
});