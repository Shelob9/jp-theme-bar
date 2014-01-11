jQuery(document).ready(function($) {
	var label = $('p#jptb_label').width();
    var push = 14 + label + 'px'
	$('#jptb-theme-bar ul li:nth-child(2)').css("margin-left", push );
});