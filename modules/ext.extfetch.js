$(function() {
	$('button.mw-extfetch-fetch').click(function() {
		var $button = $(this);
		$button.attr('disabled', 'disabled');
		
		$.ajax({
			url: mw.util.wikiScript('api'),
			data: {
				format: 'json',
				action: 'extfetch',
				extension: $button.data('extension'),
				token: mw.user.tokens.get('editToken')
			},
			type: 'POST',
		}).done(function(data) {
			if (typeof data.error == "object") {
				console.log('error!', data);
				$button.removeAttr('disabled');
			} else {
				console.log('success!', data);
				$button.replaceWith('Present'); // @fixme localize
			}
		}).fail(function(data) {
			console.log('fail!');
		});
	});
});

