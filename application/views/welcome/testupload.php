<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="/assets/jqueryupload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/assets/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/assets/js/jquery.fileupload.js"></script>
<link href="/assets/js/vendor/Trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">	
<script src="/assets/js/vendor/Trumbowyg/trumbowyg.min.js"></script>
<script src="/assets/js/vendor/Trumbowyg/plugins/upload/trumbowyg.upload.js"></script>


<textarea class="form-control txt" aria-label="With textarea" placeholder="Finish the roofing on the patio  eg." id="description" name="description"></textarea>

<script>
$( document ).ready(function() {
	$('#description')
.trumbowyg({
    btnsDef: {
        // Create a new dropdown
        image: {
            dropdown: ['insertImage', 'upload'],
            ico: 'insertImage'
        }
    },
    // Redefine the button pane
    btns: [
        ['viewHTML'],
        ['formatting'],
        ['strong', 'em', 'del'],
        ['superscript', 'subscript'],
        ['link'],
        ['image'], // Our fresh created dropdown
        ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        ['unorderedList', 'orderedList'],
        ['horizontalRule'],
        ['removeformat'],
        ['fullscreen']
    ],
    plugins: {
        // Add imagur parameters to upload plugin for demo purposes
        upload: {
            serverPath: '/project-owner/task/uploadfile',
            fileFieldName: 'files[]',
            headers: {
                'Authorization': 'Client-ID 9e57cb1c4791cea'
            },
            urlPropertyName: 'files[0].url'
        }
    }
});
});


$(function () {
	'use strict';
	$('#legalfileupload').fileupload({
        url: '/project-owner/task/uploadfile',
        dataType: 'json',
        done: function (e, data) {
			$.each(data.result.files, function (index, file) {
				if (file.error){
					alert(file.error+' - for uploaded file');
					var progress =0;
				  
					$('#progress_profile .progress-bar').css(
						'width',
						progress + '%'
					);
					$('#progress_profile').attr('data-percent', progress + '%');
					//$('#profile_photo_file').val('');
				}else {
					$('#profile_photo_file').attr('src',file.url);
					$('#profile_photo_file').show();
					$('#profile').val(file.name);
					$('#legalfile ul li').remove();
					var li = '<li>'+file.name+' &nbsp;<input name="legalfile" type="hidden" value="'+file.url+'" /><a class="removefile" href="javascript:;"><span class="badge badge-pill badge-danger">x</span></a></li>';
					$('#legalfile ul').append(li);
				}
			});
            $('.removefile').click(function(){
				$(this).parents('li').remove();
			});
        },
        progressall: function (e, data) {
        	$('#progress_profile').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_profile .progress-bar').css(
                'width',
                progress + '%'
            );
            $('#progress_profile').attr('data-percent', progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

</script>
</body>
</html>