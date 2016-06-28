
var service_hoverable	= true;
var service_timeout		= 800;
var service_offset		= {
	top : 0,
	left: 280
};

$(document).bind("ready", function(){

	$('body').addClass("javascript");	
	
	if($('#nav-toggle')){

		$('#nav-toggle').bind("click", function(){
			$('#nav').toggleClass('active');
		});

	}



	$('#remote-login-toggle').bind("click", function(){
		$('#remote-login').toggleClass('active');

		if($('#remote-login').hasClass('active')){

			$('body').bind('click', function(event){

				if(event && ($(event.target).closest("#remote-login").length || $(event.target).closest("#remote-login-toggle").length )){
					// do nothing
				}else{
					$('#remote-login').removeClass('active');
					$('body').unbind('click');
				}

			});

		}
	});



	if($('.services-content')){

		$('.services-content h3').hover(function(){

			if(!service_hoverable){
				return false;
			}

			if( $('.service-detail').length <= 0 ){
				$('.services-content').append('<div class="service-detail"><span class="service-detail-close">X</span><div class="service-detail-content"></div></div>');
				$('.service-detail-close').bind('click', function(){
					$('.service-detail').remove();

					service_hoverable = false;

					setTimeout(function(){
						service_hoverable = true;
					}, service_timeout);

				});
			}

			$('.service-detail-content').html('<span>'+$(this).html()+'</span><p>' + $(this).next('p').html() + '</p>');

			var offset = $(this).position();

			$('.service-detail').css({
				top	: (parseFloat(offset.top) + 0) + 'px',
				left: (parseFloat(offset.left) + 0) + 'px'
			});

		}, function(){

			$('service-detail').html('');

		});

	}

});
