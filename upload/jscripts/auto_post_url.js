var autoposturl_currentposturl = '', autoposturl_revert = false;
$(document).scroll(function() {
	$('.post_anchor').each(function() {
		var posturl = $(this).attr('href');

		if(posturl && autoposturl_currentposturl != posturl)
		{
			var scrollposition = window.pageYOffset;
			var distancetop = $(this).offset().top;
			var postpid = posturl.match(/#pid\d+/).toString().substr(4);
			var distancebottom = $('#bottompid'+postpid).offset().top;

			if(distancetop <= scrollposition && distancebottom >= scrollposition)
			{
				if(autoposturl_newclass)
				{
					if(typeof previousbg !== 'undefined' && previousbg.length && autoposturl_revert)
			 	 		previousbg.removeClass(autoposturl_newclass);

					previousbg = $(this).next('.post');
					if(!previousbg.hasClass('unapproved_post') && !previousbg.hasClass('deleted_post') && !previousbg.hasClass('trow_selected'))
					{
						previousbg.addClass(autoposturl_newclass);
						autoposturl_revert = true;
					}
					else
						autoposturl_revert = false;
				}

				var posttitle = $(this).attr('title');
				autoposturl_currentposturl = posturl;
				document.title = autoposturl_bbname + " - " + posttitle;

				// Alternatively use https://github.com/browserstate/History.js
				if(typeof history.pushState !== 'undefined')
					history.pushState({}, document.title, posturl);
			}
		}
	});
});