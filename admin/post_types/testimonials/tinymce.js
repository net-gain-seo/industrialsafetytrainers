jQuery(document).ready(function($) {
    //testimonial SHORTCODE BUTTON
    tinymce.create('tinymce.plugins.testimonial_plugin', {
        init : function(ed, url) {
            // Register buttons - trigger above command when clicked
            ed.addButton('testimonial_button', {
                title : 'Insert testimonial Shortcode',
                image: url + '/images/testimonial-icon.png',
                onclick : function() {
                    // triggers the thickbox
                    var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                    W = W - 80;
                    H = H - 84;
                    tb_show( 'testimonial', '#TB_inline?width=' + W + '&height=' + H + '&inlineId=testimonial-form' );
                }
            });
        }
    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('testimonial_button', tinymce.plugins.testimonial_plugin);

    var testimonialItemsHTML = '<input name="testimonial_items[]" type="text" value="Item Heading" style="margin-bottom: 10px" />';


    // executes this when the DOM is ready
    jQuery(function(){
        // creates a form to be displayed everytime the button is clicked
        // you should achieve this using AJAX instead of direct html code like this

        var formContent = '<div id="testimonial-form"><table id="testimonial-table" class="form-table">';

        if(typeof testimonial_styles != 'undefined'){
            formContent += '<tr>';
                formContent += '<th><label for="testimonial_style">Style</label></th>';
                formContent += '<td>';
                    formContent += '<select name="testimonial_style" id="testimonial_style">';
                        $.each(testimonial_styles, function(i, item) {
                            formContent += '<option value="'+item+'">'+item+'</option>';
                        });

                    formContent += '</select>';
                    formContent += '<br />';
                formContent += '</td>';
            formContent += '</tr>';
        }

        formContent += '<tr>\
				<th><label for="testimonial_title">Title</label></th>\
				<td><input type="text" id="testimonial_title" name="testimonial_title" value="" /><br />\
				</td>\
			</tr>\
            <tr>\
				<th><label for="testimonial_type">Type</label></th>\
				<td><select id="testimonial_type" name="testimonial_type"><option value="standard">Standard</option><option value="carousel">Carousel</option></select><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="testimonial_scrollDirection">Scroll Direction</label></th>\
				<td>\
				    <select id="testimonial_scrollDirection" name="testimonial_scrollDirection">\
				        <option value="left">Left</option>\
				        <option value="up">Up</option>\
				    </select><br />\
				</td>\
			</tr>\
            <tr class="full_width_only">\
			    <th><h3>Other Options</h3></th>\
			    <th></th>\
			</tr>\
            <tr>\
				<th><label for="testimonial_id">ID</label></th>\
				<td><input type="text" id="testimonial_id" name="testimonial_id" value="" /><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="testimonial_class">Class</label></th>\
				<td><input type="text" id="testimonial_class" name="testimonial_class" value="" /><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="testimonial_offset">Offset</label></th>\
				<td><input type="text" id="testimonial_offset" name="testimonial_offset" value="0" /><br />\
				</td>\
			</tr>\
			<tr>\
				<th><label for="testimonial_posts_per_page">Posts Per Page</label></th>\
				<td><input type="text" id="testimonial_posts_per_page" name="testimonial_posts_per_page" value="-1" /><br />\
				</td>\
			</tr>\
			</tr>\
		</table>\
		<p class="submit">\
			<input type="button" id="testimonial-submit" class="button-primary" value="Insert testimonial" name="submit" />\
		</p>\
		</div>';

        var form = jQuery(formContent);

        var table = form.find('table');
        form.appendTo('body').hide();

        // handles the click event of the submit button
        form.find('#testimonial-submit').click(function(){
            var shortcode = '';
            var testimonial_style = table.find('#testimonial_style').val();
            var testimonial_type = table.find('#testimonial_type').val();
            var testimonial_id = table.find('#testimonial_id').val();
            var testimonial_class = table.find('#testimonial_class').val();
            var testimonial_offset = table.find('#testimonial_offset').val();
            var testimonial_scrollDirection = table.find('#testimonial_scrollDirection').val();
            var testimonial_posts_per_page = table.find('#testimonial_posts_per_page').val();
            var testimonial_title = table.find('#testimonial_title').val();

                shortcode += '<p>[testimonials '+(testimonial_type == "carousel" ? 'carousel=\"yes"':"")+' \
                '+(testimonial_id != "" ? 'id=\"'+testimonial_id+'\"':"")+' \
                '+(testimonial_class != "" || testimonial_style != undefined || testimonial_type != undefined ? 'class=\"'+testimonial_class+ ' '+(testimonial_type != undefined ? testimonial_type:"")+' '+(testimonial_style != undefined ? testimonial_style:"")+'\"':"")+'\
                '+(testimonial_offset != "" ? 'offset=\"'+testimonial_offset+'\"':"")+' \
                '+(testimonial_posts_per_page != "" ? 'posts_per_page=\"'+testimonial_posts_per_page+'\"':"")+' \
                '+(testimonial_title != "" ? 'title=\"'+testimonial_title+'\"':"")+' \
                '+(testimonial_scrollDirection != "" ? 'direction=\"'+testimonial_scrollDirection+'\"':"")+' \
                ]</p>';

            jQuery('input[name="testimonial_items[]"]').each(function() {
                var heading = $(this).val();
                shortcode += '<p>[testimonial_item title="'+heading+'"]</p><p>Content Goes Here...</p><p>[/testimonial_item]</p>';
            });

            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

            tb_remove();
        });

        form.find('#addtestimonialItem').click(function(){
            jQuery('#additionaltestimonialItems').append('<div>'+testimonialItemsHTML+' - <a class="removeItem" href="#">Remove</a></div>');
            return false;
        });

        jQuery(document).on('click','.removeItem',function(){
            jQuery(this).parent('div').remove();
            return false;
        });
    });
});