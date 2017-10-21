/*Huang JS Document QQ:52710360 */

(function() {
	$('.customer-logo').find('.customer-box').each(function() {
		$(this).hover(function() {
			$(this).find('.am-active').show();
			$(this).find('.normal-logo').hide();
		}, function() {
			$(this).find('.am-active').hide();
			$(this).find('.normal-logo').show();
		})
	});
})()