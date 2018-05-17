
(function($){
	"use strict"; $(document).ready(function(){	
		$(".banner-image").backstretch('images/banner.jpg');
		$(window).scroll(function() {
			if (($(".header.fixed").length > 0)) { 
				if(($(this).scrollTop() > 0) && ($(window).width() > 767)) {
					$("body").addClass("fixed-header-on");
				} else {
					$("body").removeClass("fixed-header-on");
				}
			}
		});
})(this.jQuery);