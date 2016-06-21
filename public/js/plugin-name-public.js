(function( $ ) {
	// 'use strict';

	$(document).ready(function() {
      $('#wp-admin-bar-root-default').append('<li id="wp-admin-bar-edit-live"><a class="ab-item" disabled href="javascript:void(0);">Save</a></li>');
    var elements = document.querySelectorAll('.editableHD');
        editor = new MediumEditor(elements);
        // console.log(elements);
    });


	$(document).ready(function() {
	    var textInputs = $('[contenteditable]');
	    textInputs.each(function() {
	    	var contents =$(this).html();
	    	$(this).on('focus', function() {
	    		// console.log($(this).html());
	    	}).on('blur', function() {
	    		// console.log($(this).html());
	    		if (contents!=$(this).html()){
	    	    $(this).addClass('textChanged');
	    	    $('#wp-admin-bar-edit-live a').text('Save unsaved progress');
	    	    $('#wp-admin-bar-edit-live a').removeAttr('disabled');
	    	    contents = $(this).html();
	    		}
	    	});
	    });
    });

	$('#wp-admin-bar-edit-live').on('click',function() {
		var editableText = $('[contenteditable].textChanged');
		var textString = [];
		editableText.each(function() {
			var text = $(this).html();
			var key  = $(this).data('key');
			var name = $(this).data('name');
			var postid = $(this).data('postid');
			var textArr = [key, text, name, postid];
			textString.push(textArr);
		});

		console.log(textString);

	  jQuery.ajax({
	      url: meta.ajaxurl,
	      data: {
	          'action' : 'update_texts',
	          'siteID' : meta.page.ID,
	          'textArr': textString
	      },

	      success:function(data){
	          $('body').append('<div class="alert alert-success alert-dismissible alert-on-top" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Success!</strong> Changes have been saved!</div>');
	      		// console.log(data);
	      		textString = [];
	      		$('#wp-admin-bar-edit-live a').text('Save');
	      },
	      error: function(errorThrown){
	          console.log('errorThrown');
	      }
	  });

	});


})( jQuery );
