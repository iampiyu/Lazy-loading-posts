
//Add it in ready fun in your JS file


if (jQuery("body").hasClass("page-template-page-referanser")) {  //Your Page template Name goes here

	// Fetch next 3 referancer on Page Scroll
	// if (jQuery(window).width() >= 768) {	 
	var inside_loop = false;
	jQuery(window).scroll(function (event) {
		// console.log(inside_loop);
		if (inside_loop == false) {
			//console.log("insidescroll");
			var position = jQuery(window).scrollTop();


			if (jQuery(window).width() < 768) {
				var min = (jQuery(document).height() - jQuery(window).height()) - (jQuery("#colophon").height() + 1500); //#colophon is the last Section id
			} else if (jQuery(window).width() >= 768 && jQuery(window).width() < 992) {
				var min = (jQuery(document).height() - jQuery(window).height()) - (jQuery("#colophon").height() + 1100);
			} else if (jQuery(window).width() >= 992) {
				var min = (jQuery(document).height() - jQuery(window).height()) - (jQuery("#colophon").height() + 500);
			}
			var max = min + 100;
			//console.log(position);	
			//console.log(min + '-' + max);	
			if (min <= position && position <= max) {
				inside_loop = true;
				// console.log("innnnnnnnnn");
				//setTimeout(function () {
				var offset = jQuery('.blog_list').attr('data-offset');
				//console.log(offset);

				//if (offset != null){
				jQuery('.ref-box-loader-wrap').show();
				jQuery('.ref-loader').show();
				//  Show loader before loading the required Posts (optional)

				jQuery.ajax({
					url: ajax_object.ajax_url,
					data: {
						// catslug: cat_slug,
						offset: parseInt(offset),
						action: "load_moreblog",
					},
					// dataType: 'JSON',
					type: 'POST',
					// async: false,
					// beforeSend: function (msg) { }

					success: function (output) {

						jQuery('.ref-box-loader-wrap').hide();
						jQuery('.ref-loader').hide();
						//  hide loader after loading the required Posts (optional)

						var outputD = jQuery.parseJSON(output);
						var datahtml = '';

						var rownumcount = 0;
						//console.log(outputD);
						jQuery(outputD.data).each(function (index, value) {
							var title = value.title;
							var linktdetail = value.linktdetail;
							// var button_text = value.button_text;
							// console.log(value.linktdetail);

							// if(value.button_text == ""){
							// var button_text = "Button";
							// }else{
							var button_text = value.button_text;
							//}
							if (button_text) {
								btn_text = '<div class="btn-wrapper"> <a href="' + linktdetail + '">' + button_text + '</a></div>'
							} else {
								btn_text = ''
							}

							if (value.excerpt == "null") {
								var excerpttext = "";
							} else {
								var excerpttext = value.excerpt;
							}
							var image = value.image;

							datahtml = '<div class="blog-refferencer blogmarginTB"> <article><a href="' + linktdetail + '"><div class="img-wrapper" style="background-image: url(' + image + ')"></div></a><div class="text-wrapper"><a href="' + linktdetail + '"><h4>' + title + '</h4></a> <p class="regular-text">' + excerpttext + '</p> ' + btn_text + ' </div></article></div>';

							//jQuery('.blog_list').data('offset', outputD.offset);
							// console.log(outputD.data);
							if (outputD.offset == null) {
								//jQuery(".blogimg").removeClass("lazy");
								//jQuery(".blogimg").attr('src', '');
								//jQuery(".box-loader-wrap").remove();
							}
							jQuery('.blog_list').attr('data-offset', outputD.offset);
							if (datahtml != '') {
								jQuery('.blog_list').height() + 760;
								jQuery('.ref_listing').append(datahtml);
								inside_loop = false;
							}
						});


						//jQuery(element).find('.box-loader-wrap').fadeOut('slow');


						jQuery('.lazy').lazy({
							threshold: 200,
							effect: 'fadeIn',
							afterLoad: function (element) {
								//console.log(element);
								// called after an element was successfully handled
								jQuery(element).find('.box-loader-wrap').fadeOut('slow');
							}
						});

						if (jQuery(window).width() >= 768) {
							jQuery(".ref_listing .blog-refferencer .text-wrapper ").responsiveEqualHeightGrid();
						};
					}

				});

				//}
				// else if( offset == jQuery('.blog_list').attr('data-offset')){}
				//	 }, 150);
			}
		} else {
			event.preventDefault();
		}
	});

}