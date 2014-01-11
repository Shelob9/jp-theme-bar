jQuery(document).ready(function($) {
    var farb = function( cp, id ) { 
        $( cp ).hide();
        $( cp).farbtastic( id );
        $( id ).click(function() {
            $( cp ).fadeIn();
        });
        $(document).mousedown(function() {
            $( cp ).each(function() {
                var display = $(this).css('display');
                if ( display == 'block' )
                    $(this).fadeOut();
            });
        });
    }

    farb( '#farb-cp-1', '#jptb_bg_colour' );
    farb( '#farb-cp-2', '#jptb_text_colour' );
    farb( '#farb-cp-3', '#jptb_label_bg_colour' );
    farb( '#farb-cp-4', '#jptb_label_text_colour' );

});
