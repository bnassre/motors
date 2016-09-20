(function($) {
	'use strict'

	$(document).ready(function ($) {
		
		$('.stm-automanager-xml select').material_select();
		
		$('.stm_edit_item').click(function(e){
			e.preventDefault();
			var edit_item_id = $(this).attr('data-id');
			var edit_item_name = $(this).attr('data-name');
			var edit_item_plural = $(this).attr('data-plural');
			var edit_item_slug = $(this).attr('data-slug');
			var edit_item_numeric = $(this).attr('data-numeric');
			var edit_item_use_on_listing = $(this).attr('data-use-on-listing');
			var edit_item_use_on_car_listing = $(this).attr('data-use-on-car-listing');
			var edit_item_use_on_car_archive_listing = $(this).attr('data-use-on-car-archive-listing');
			var edit_item_use_on_single_car_page = $(this).attr('data-use-on-single-car-page');
			var edit_item_use_on_filter = $(this).attr('data-use-on-car-filter');
			var edit_item_use_on_modern_filter = $(this).attr('data-use-on-car-modern-filter');
			var edit_item_use_on_modern_filter_view_images = $(this).attr('data-use-on-car-modern-filter-view-images');
			var edit_item_use_on_filter_links = $(this).attr('data-use-on-car-filter-links');
			var use_on_directory_filter_title = $(this).attr('data-use-on-directory-filter-title');
			var data_number_field_affix = $(this).attr('data-number-field-affix');
			var edit_font = $(this).attr('data-font');
			var listing_rows = $(this).attr('data-listing-rows-numbers');
			var listing_taxonomy_parent = $(this).attr('data-use-listing_taxonomy_parent');
			var enable_checkbox_button = $(this).attr('data-enable-checkbox-button');
			var use_in_footer_search = $(this).attr('data-use-in-footer-search');

			$('#listing_taxonomy_parent').val(listing_taxonomy_parent);
			$('#listing_cols_per_row').val(listing_rows);

			$('#stm_edit_item_single_name').val( edit_item_name);
			$('#stm_edit_item_plural_name').val( edit_item_plural);
			$('#stm_edit_item_slug').val( edit_item_slug);

			$('#stm_number_field_affix').val(data_number_field_affix);
			$('#stm_old_slug').val(edit_item_slug);
			$('.stm_edit_item_wrap #stm_edit_item_id').val(edit_item_id);

			if(edit_item_numeric == 1) {
				$('#stm_edit_item_numeric').prop('checked', true);
			} else {
				$('#stm_edit_item_numeric').prop('checked', false);
			}

			if(edit_item_use_on_listing == 1) {
				$('#use_on_single_listing_page').prop('checked', true);
			} else {
				$('#use_on_single_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_listing == 1) {
				$('#use_on_car_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_listing == 1) {
				$('#use_on_car_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_car_archive_listing == 1) {
				$('#use_on_car_archive_listing_page').prop('checked', true);
			} else {
				$('#use_on_car_archive_listing_page').prop('checked', false);
			}

			if(edit_item_use_on_single_car_page == 1) {
				$('#use_on_single_car_page').prop('checked', true);
			} else {
				$('#use_on_single_car_page').prop('checked', false);
			}

			if(edit_item_use_on_filter == 1) {
				$('#use_on_car_filter').prop('checked', true);
			} else {
				$('#use_on_car_filter').prop('checked', false);
			}

			if(edit_item_use_on_modern_filter == 1) {
				$('#use_on_car_modern_filter').prop('checked', true);
			} else {
				$('#use_on_car_modern_filter').prop('checked', false);
			}

			if(edit_item_use_on_modern_filter_view_images == 1) {
				$('#use_on_car_modern_filter_view_images').prop('checked', true);
			} else {
				$('#use_on_car_modern_filter_view_images').prop('checked', false);
			}

			if(edit_item_use_on_filter_links == 1) {
				$('#use_on_car_filter_links').prop('checked', true);
			} else {
				$('#use_on_car_filter_links').prop('checked', false);
			}

			if(use_on_directory_filter_title == 1) {
				$('#use_on_directory_filter_title').prop('checked', true);
			} else {
				$('#use_on_directory_filter_title').prop('checked', false);
			}

			if(typeof edit_font != 'undefined') {
				$('#stm-edit-picked-font-icon').val(edit_font);
				$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview').html('<i class="' + edit_font + '"></i>')
			} else {
				$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview i').remove();
			}

			if(enable_checkbox_button == 1) {
				$('#enable_checkbox_button').prop('checked', true);
			} else {
				$('#enable_checkbox_button').prop('checked', false);
			}

			if(use_in_footer_search == 1) {
				$('#use_in_footer_search').prop('checked', true);
			} else {
				$('#use_in_footer_search').prop('checked', false);
			}

			$('.stm_edit_item_wrap').slideDown();
			
			$('.stm-new-filter-category').slideUp();
		});

		$('.stm_delete_item').click(function(e){
			var confirm_delete = confirm('Are you sure?');
			if(!confirm_delete){
				e.preventDefault();
			}
		});

		$('.stm_close_edit_item').click(function(e){
			e.preventDefault();
			$('.stm_edit_item_wrap').slideUp();
			$('.stm-new-filter-category').slideDown();
		});

		//Sort
		$(function() {
			$( ".stm-ui-sortable" ).sortable();
			$( ".stm-ui-sortable" ).disableSelection();
		});

		$(".stm-ui-sortable").sortable({
			update: function (event, ui) {
				var r = $(this).sortable("toArray");
				$('#stm_new_posts_order').val(r);
			}
		}).disableSelection();

		$('.stm_theme_pick_font').click(function(e){
			e.preventDefault();
			$(this).closest('.stm_theme_font_pack_holder').find('.stm_theme_icon_font_table').slideToggle();
		});

		$('.stm-new-filter-category .stm-pick-icon').click(function(e){
			e.preventDefault();
			var font = $(this).find('i').attr('class');
			$('.stm-new-filter-category #stm-picked-font-icon').val(font);
			$('.stm-new-filter-category .stm_theme_cat_chosen_icon_preview').html('<i class="' + font + '"></i>')
		});

		$('.stm_edit_item_wrap .stm-pick-icon').click(function(e){
			e.preventDefault();
			var font = $(this).find('i').attr('class');
			$('.stm_edit_item_wrap #stm-edit-picked-font-icon').val(font);
			$('.stm_edit_item_wrap .stm_theme_cat_chosen_icon_edit_preview').html('<i class="' + font + '"></i>')
		});
		
		
		$(".source .item").draggable({ 
			revert: "invalid", appendTo: 'body', helper: 'clone',
        	start: function(ev, ui){ 
	        	ui.helper.width($(this).width()); 
	        }
    	});

	    $(".target .empty").droppable({ 
		    tolerance: 'pointer', 
		    hoverClass: 'highlight', 
		    drop: function(ev, ui){
	            var item = ui.draggable;
	            if (!ui.draggable.closest('.empty').length) item = item.clone().draggable();
	            this.innerHTML = '';                               
	            item.css({ top: 0, left: 0 }).appendTo(this);
				$(item).closest('.target-unit').find('input').val(ui.draggable[0].dataset.key);
	        },
	        out: function(ev, ui) {
		        var item = ui.draggable;
		        $(item).closest('.target-unit').find('input').val('');
	        },
	        activate: function(ev, ui) {
	        },
	        create: function(ev, ui) {
	        },
	        deactivate: function(ev, ui) {
	        },
	        over: function(ev, ui) {
	        }
	    });
	    
	    
	    
	
	    $(".target").on('click', '.closer', function(){
	        var item = $(this).closest('.item');
	        item.fadeTo(200, 0, function(){ item.remove(); })
			$(this).closest('.target-unit').find('input').val('');
	    });
	    
	    
	    // AJAX CSV
		$('#stm_import_from_csv button').on("click", function(event){
			$('.import_details').slideDown();
		});
		
		// First step AJAX automanager
		var current_step = 1;
		$('#stm_automanager_first_step').on("submit", function(event){
            event.preventDefault();
            $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $( this ).serialize() + '&action=stm_ajax_file_automanager_upload',
                beforeSend: function(){
	                $('.stm-preloader-file .preloader-wrapper').addClass('active');
                },
                success: function (data) {
					
	                $('.stm-preloader-file .preloader-wrapper').removeClass('active');
	                
	                if(typeof data.errors.url !== 'undefined') {
						$('input#url').addClass('invalid');
	                } 
	                if(data.errors.length == 0) {
		                current_step = 2;
		                // Show all file data
		                var xmlData = data.xml.Vehicle;
		                
		                if(xmlData.length) {
		                	for(var key in xmlData[0]) {
			                	
								$('#stm_automanager_second_step .xml_parts').append('<div><div class="xml_badge" data-key="{'+key+'}">' + key + '</div></div>');
								if(data.filter.hasOwnProperty(key)) {
									$('input[name="' + data.filter[key] +  '"]').val('{' + key +  '}');
								}
			                	
		                	}
		                }
		                
		                $('.xml_parts div.xml_badge').draggable({
							revert: true, 
							helper: "clone"
		                });
		                
		                $('.stm-automanager-xml').removeClass('first-step');
		                $('.stm-automanager-xml').addClass('second-step');
		                $('#stm_automanager_first_step').slideUp();
		                
					
						$('.progress .determinate').css({
							width: '50%'
						});
						
						$('#stm_automanager_second_step').slideDown();
					}
                }
            });
        });
        
        // Second step asscociations
        $('.stm-proceed-step-three').click(function(e){
	        e.preventDefault();
	        $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $('#stm_automanager_second_step').serialize() + '&action=stm_ajax_automanager_save_associations',
                beforeSend: function(){
	                $('input[name="title"],input[name="content"]').removeClass('invalid');
	                $('.stm-step-two-preloader .preloader-wrapper').addClass('active');
                },
                success: function (data) {
					
	                $('.stm-step-two-preloader .preloader-wrapper').removeClass('active');
	                
	                if(typeof data.errors.title != 'undefined') {
		                $('input[name="title"]').addClass('invalid');
		                $('html, body').stop().animate({
							scrollTop: 0
						}, 700);
	                }
	                
	                if(typeof data.errors.title != 'undefined') {
		                $('input[name="content"]').addClass('invalid');
		                $('html, body').stop().animate({
							scrollTop: 0
						}, 700);
	                }
	                
	                if(data.errors.length == 0) {
		                current_step = 3;
		                
		                $('.stm-automanager-xml').removeClass('second-step');
		                $('.stm-automanager-xml').addClass('third-step');
		                $('#stm_automanager_second_step').slideUp();
		                
					
						$('.progress .determinate').css({
							width: '75%'
						});
						
						$('#stm_automanager_third_step').slideDown();
	                }
		                
                }
            });
		});
		
		// Third step settings template
        $('.stm-proceed-step-four').click(function(e){
	        e.preventDefault();
	        $.ajax({
                url: ajaxurl,
                type: "POST",
                dataType: 'json',
                context: this,
                data: $('#stm_automanager_third_step').serialize() + '&action=stm_ajax_automanager_save_template',
                beforeSend: function(){
	                $('input[name="template_name"]').removeClass('invalid');
	                $('.stm-step-three-preloader .preloader-wrapper').addClass('active');
                },
                success: function (data) {
	                $('.stm-step-three-preloader .preloader-wrapper').removeClass('active');
	                
	                if(typeof data.errors.template_name != 'undefined') {
		                $('input[name="template_name"]').addClass('invalid');
		                $('html, body').stop().animate({
							scrollTop: 0
						}, 700);
	                }
	                	                
	                if(data.errors.length == 0) {
		                current_step = 4;
		                
		                $('.stm-automanager-xml').removeClass('third-step');
		                $('.stm-automanager-xml').addClass('fourth-step');
		                $('#stm_automanager_third_step').slideUp();
						//if(typeof data.run_import_now !== 'undefined' && data.run_import_now) {
							$('#stm_automanager_fourth_step iframe').attr('src', window.location.href + '&stm_xml_do_import_automanager=1');
							$('#stm_xml_import_automanager').slideDown();
						//}
						$('#stm_automanager_fourth_step').slideDown();
	                }
		                
                }
            });
		})
        
        $('.stm-back').click(function(e){
	        e.preventDefault();
			switch(current_step) {
			    case 2:
					$('.stm-automanager-xml').addClass('first-step');
		            $('.stm-automanager-xml').removeClass('second-step');
		            $('#stm_automanager_first_step').slideDown();
		            $('#stm_automanager_second_step').slideUp();
		            $('.progress .determinate').css({
						width: '25%'
					});
					$('.xml_parts').empty();
					current_step = 1;
			        break;
			    case 3:
					$('.stm-automanager-xml').addClass('second-step');
		            $('.stm-automanager-xml').removeClass('third-step');
		            $('#stm_automanager_second_step').slideDown();
		            $('#stm_automanager_third_step').slideUp();
		            $('.progress .determinate').css({
						width: '50%'
					});
					current_step = 2;
			        break;
			    case 4:
			    	$('#stm_automanager_fourth_step iframe').attr('src', 'about:blank');
					$('.stm-automanager-xml').addClass('third-step');
		            $('.stm-automanager-xml').removeClass('fourth-step');
		            $('#stm_automanager_third_step').slideDown();
		            $('#stm_automanager_fourth_step').slideUp();
		            $('.progress .determinate').css({
						width: '75%'
					});
					current_step = 3;
			        break;
			}
        });
        
        $('.update_now_test').click(function(e){
	        e.preventDefault();
	        $('#stm_automanager_fourth_step iframe').attr('src', window.location.href + '&stm_xml_do_import_automanager=1');
        })
        
        $(".stm_theme_fields .input-field > input, .stm_theme_fields .input-field textarea").droppable({
		  drop: function(event, ui) {
		  	var textToAdd = ui.draggable.data('key');
		  	var currentValue = $(this).val();
		  	var stmPrefix = '';
		  	if(currentValue.length > 0) {
			  	stmPrefix = ' ';
		  	}
		  	$(this).val(currentValue + stmPrefix + textToAdd);
		  },
		  activate: function(ev, ui) {
			  $('#stm_automanager_second_step .row .col.s8').addClass('pulsate');
	      },
	      deactivate: function(ev, ui) {
			  $('#stm_automanager_second_step .row .col.s8').removeClass('pulsate');
			  $(this).removeClass('hovered');
		  },
		  over: function(ev, ui){
		  	  $(this).addClass('hovered');
		  },
		  out: function(ev, ui){
		  	  $(this).removeClass('hovered');
		  },
		});
	   
	});

	
}(jQuery));