jQuery(document).ready(function($) {
    $('#colorpicker').hide();
    $('#colorpicker').farbtastic('#jptb_bg_colour');


    $('#jptb_bg_colour').click(function() {
        $('#colorpicker').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
	
	

	$('#colorpicker2').hide();
	$('#colorpicker2').farbtastic('#jptb_text_colour');
	
	$('#jptb_text_colour').click(function() {
        $('#colorpicker2').fadeIn();
    });
	$(document).mousedown(function() {
        $('#colorpicker2').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
    
    
    
    //label colours
    
    $('#colorpicker').hide();
    $('#colorpicker3').farbtastic('p#jptb_label');


    $('#jptb_bg_colour').click(function() {
        $('#colorpicker').fadeIn();
    });

    $(document).mousedown(function() {
        $('#colorpicker').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
	
	

	$('#colorpicker4').hide();
	$('#colorpicker4').farbtastic('p#jptb_label');
	
	$('#jptb_text_colour').click(function() {
        $('#colorpicker4').fadeIn();
    });
	$(document).mousedown(function() {
        $('#colorpicker4').each(function() {
            var display = $(this).css('display');
            if ( display == 'block' )
                $(this).fadeOut();
        });
    });
	
});