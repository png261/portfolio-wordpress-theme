(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
function matchCustom(params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
      return data;
    }

    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
      return null;
    }

    // `params.term` should be the term that is used for searching
    // `data.text` is the text that is displayed for the data object
    if (data.text.indexOf(params.term) > -1) {
      var modifiedData = $.extend({}, data, true);
      modifiedData.text += ' (matched)';

      // You can return modified objects from here
      // This includes matching the `children` how you want in nested data sets
      return modifiedData;
    }

    // Return `null` if the term should not be displayed
    return null;
}

	 $(document).ready(function(){
	   	 
        var selectSimple = $('.js-select-simple');
    
        selectSimple.each(function () {
            var that = $(this);
            var selectBox = that.find('select');
            var selectDropdown = that.find('.select-dropdown');
            selectBox.select2({
                dropdownParent: selectDropdown,
                 matcher: function(params, option) {
			    // If there are no search terms, return all of the option
			    var searchTerm = $.trim(params.term);
			    if (searchTerm === '') { return option; }

			    // Do not display the item if there is no 'text' property
			    if (typeof option.text === 'undefined') { return null; }

			    var searchTermLower = searchTerm.toLowerCase(); // `params.term` is the user's search term

			    // `option.id` should be checked against
			    // `option.text` should be checked against
			    var searchFunction = function(thisOption, searchTerm) {
			        return thisOption.text.toLowerCase().indexOf(searchTerm) > -1 ||
			            (thisOption.id && thisOption.id.toLowerCase().indexOf(searchTerm) > -1);
			    };

			    if (!option.children) {
			        //we only need to check this option
			        return searchFunction(option, searchTermLower) ? option : null;
			    }

			    //need to search all the children
			    option.children = option
			        .children
			        .filter(function (childOption) {
			            return searchFunction(childOption, searchTermLower);
			        });
			    return option;
			}
            });
        });
	 });

$(document).on("click", ".export-btn", function(e){
	e.preventDefault();
	var page_id = $('#export_pages').val();
	var replace_urls = $('#replace_all_url').is(':checked') ? true : false;

	if(page_id !== null){
	 var datas = {
	  'action': 'rc_export_wp_page_to_static_html',
	  'rc_nonce': rcewpp.nonce,
	  'page_id': page_id,
	  'replace_urls': replace_urls,
	};

	$('.logs_list').html('');
	$('.spinner_x').removeClass('hide_spin');
	$('.download-btn').addClass('hide');
	$('.logs').show();


	var myVar = setInterval(myTimer, 1000);

	function myTimer() {
	   var id = $('.logs_list .log').length;
	   var datas2 = {
	    'action': 'get_exporting_logs',
	    'rc_nonce': rcewpp.nonce,
	    'log_id': id,
	  };
	  
	  $.ajax({
	      url: rcewpp.ajax_url,
	      data: datas2,
	      type: 'post',
	      dataType: 'json',
	  
	      beforeSend: function(){
	      },
	      success: function(r){
	        if(r.success == 'true'){
	        	if (r.response !== null) {

	        		$.each(r.response, function(i, v){
	        			var type = "";
		        		if (v.type == "copying") {
		        			type = '<span class="copying log_type">Copying</span>';
		        		} 
		        		if(v.type == "reading") {
		        			type = '<span class="reading log_type">Reading</span>';
		        		}
		        		if(v.type == "creating") {
		        			type = '<span class="creating log_type">Creating</span>';
		        		}
		        		if(v.type == "creating_last_file") {
		        			type = '<span class="creating log_type">Creating</span>';
		        		}
		        		if(v.type == "replacing") {
		        			type = '<span class="replacing log_type">Replacing</span>';
		        		}

		        		var log_text = '<span class="path">' + v.path + '</span>';
		        		var comment = '<span class="comment">' + v.comment + '</span>';

		        		var log = '<div class="log" id="'+v.id+'">'+type+' ' +log_text+ ' ' + comment + '</div>';

						if ($('#'+v.id).length < 1) {
			        		$('.logs_list').prepend(log);

			        		if(v.type == "creating_last_file") {
			        			myStopFunction();
			        		}
						}
	        		})
	        		
				}

	          } else {
	            alert('Something went wrong, please try again!');
				$('.spinner_x').addClass('hide_spin');
	          }
	      	
	      }, error: function(){
				$('.spinner_x').addClass('hide_spin');
	      	
	    }
	  });
	}

	function myStopFunction(error = false) {
		clearInterval(myVar);

		if (!error) {
			var log = '<div class="log" id="created"><span class="log_type creating_main_file">Success</span><span> the main html file has been created!</span></div>';
			if ($('.creating_main_file').length < 1) {
				$('.logs_list').prepend(log);
			}
			setTimeout(function() {
				var log = '<div class="log" id="creating_zip_file"><span class="log_type creating creating_zip_file">Creating</span><span> zip file.</span></div>';
				if ($('.creating_zip_file').length < 1) {
					$('.logs_list').prepend(log);	
				}
			}, 50);
		}

	} 
	

	$.ajax({
	    url: rcewpp.ajax_url,
	    data: datas,
	    type: 'post',
	    dataType: 'json',
	
	    beforeSend: function(){
			
	    },
	    success: function(r){
	      if(r.success == 'true'){
	      	console.log(r);
	      	if (r.response) {

	      		setTimeout(function() {

	       		var datas = {
				  'action': 'create_the_zip_file',
				  'rc_nonce': rcewpp.nonce,
				  'page_id': page_id,
				};
				
				$.ajax({
				    url: rcewpp.ajax_url,
				    data: datas,
				    type: 'post',
				    dataType: 'json',
				
				    beforeSend: function(){
						
				    },
				    success: function(r){

				      if(r.success == 'true' && r.response !== false ){
				        
				        console.log(r.response);
						setTimeout(function() {
							myStopFunction();
							var log = '<div class="log" id="created_zip_file"><span class="log_type created_zip_file">Success</span><span> the zip file has been created!</span></div>';
							$('.logs_list').prepend(log);

							setTimeout(function() {
								$('.spinner_x').addClass('hide_spin');
								var log = '<div class="log" id="ready_to_download"><span class="log_type ready_to_download"></span><span> The file is ready to download.</span></div>';
								$('.logs_list').prepend(log);

								$('.download-btn').attr('href', r.response).removeClass('hide');

							}, 1000);
						}, 1500);
				        
				        } else {
				          alert('Something went wrong, please try again!');
							$('.spinner_x').addClass('hide_spin');
				        }
				    	
				    }, error: function(){
							$('.spinner_x').addClass('hide_spin');
				    	
				  }
				});

	      	}, 1000);

	      	}
	
	        
	        } else {
	        	myStopFunction(true);
	          alert('Something went wrong, please try again!');
				$('.spinner_x').addClass('hide_spin');
	        }
	    	
	    }, error: function(){
	    	myStopFunction(true);
			$('.spinner_x').addClass('hide_spin');
	  }
	});
	
	}
});

})( jQuery );
