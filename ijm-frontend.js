jQuery(document).ready(function($) {
	var label = $('p#ijmtb_label').width();
    var push = 14 + label + 'px'
	$('#ijm-theme-bar ul li:nth-child(2)').css("margin-left", push );
});