jQuery(document).ready(function() {

	// Handle image uploads
	var file_frame;
	
	jQuery('.upload-image').live('click', function(event) {
        
        // Set target fields
		targetfield = jQuery(this).siblings('input[type="hidden"]');
		previewimage = jQuery(this).parent().prev().children('img');
 
        event.preventDefault();
 
        // If the uploader object has already been created, reopen the dialog
        if (file_frame) {
            file_frame.open();
            return;
        }
 
        // Extend the wp.media object
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        // When a file is selected, grab the URL and set it as the text field's value
        file_frame.on('select', function() {
		
            attachment = file_frame.state().get('selection').first().toJSON();
            targetfield.val(attachment.url);
            previewimage.attr('src', attachment.url);
            
        });
 
        // Open the uploader dialog
        file_frame.open();
 
    });
    
    jQuery('.remove-image').live('click', function(event) {
        
        // Set target fields
		targetfield = jQuery(this).siblings('input[type="hidden"]');
		previewimage = jQuery(this).parent().prev().children('img');
 
        event.preventDefault();
        
        targetfield.val('');
        previewimage.remove();
        $(this).remove();
        
    });
	
	// Add GUI colorpicker
	//jQuery('.minicolors').minicolors();

});
