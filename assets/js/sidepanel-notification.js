$(window).load(function() {
    jQuery(window).resize(function() {
        var windowHeight = jQuery(window).height() - 60.22;
        jQuery('.notification-body-height').css({'height': windowHeight});
    });

    jQuery(window).trigger('resize');
});

jQuery(document).ready(function(){
    jQuery('.notification-btn-link').click(function(){
		if(jQuery('.notification-slider-closed').is(':visible')){						
			/* Close notification --------------*/
            jQuery(".bg-blackHeader-notification-overlay").stop(true, true).show().animate({
                width:'100%'
            },300,function(){
                jQuery(".bg-notification-overlay").stop(true, true).show().animate({
                    width:'100%'
                },800,function(){
                    jQuery('.notification-fixed.notification-slider-closed').stop(true, true).animate({
                        width: '0'
                    },300,function(){
                        $(this).hide();
                    });
                });
            });
		}else{
			/* Open notification --------------*/
            jQuery('.notification-fixed.notification-slider-closed').stop(true, true).show().animate({
                    width:'320px'
                },function(){
                jQuery(".bg-notification-overlay").stop(true, true).animate({
                    right: 'auto',
                    width:'0px'
                },300,function(){
                    jQuery(this).hide();
                    jQuery(".bg-blackHeader-notification-overlay").stop(true, true).animate({
                        right: 'auto',width:'0px'
                    },500,function(){
                        $(this).hide();
                    });
                });
            });
		}				
        return false;
    });
    
});