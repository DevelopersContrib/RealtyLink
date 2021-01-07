<form class="addprojectform" action="javascript:processProject();">
	<div class="form-group">
		<label>Project Title</label>
		<input type="text" class="form-control" id="project_title" value="<?php echo $title?>"> 
		<span class="txt-alert" style="display:none">Please enter project title!</span>
	</div>
	<div class="form-group">
		<label>Project Description</label>
		<textarea class="form-control" aria-label="With textarea" id="project_desc"><?php echo stripcslashes($description)?></textarea>
		<span class="txt-alert" style="display:none">Please enter project description!</span>
	</div>
	<div class="form-group" id="">
		<label>Upload project icon</label>
		<input type="file" class="form-control-file" id="project_photo">
		<span class="txt-alert" style="display:none">Please upload an image!</span>
		 <div class="progress pos-rel col-xs-10 col-sm-5" id="progress-project-photo" style="display:none">
													<div class="progress-bar"></div>
                     	</div>
         <?php if ($icon_image != ""):?>
            <img src="<?php echo $icon_image?>" class="img-fluid" id="img_project" >
         	<?php else:?>
         	<img src="" class="img-fluid" id="img_project" style="display: none">
         <?php endif?>            	
         
	</div>
	
	<div class="form-group" id="" <?php if ($project_id>0):?> style="display:none"<?php endif?>>
		<label>Network</label>
		<select  class="form-control" id="project_network">
				<option value="test" <?php if ($network == 'test') echo 'selected'?>>test</option>
				<!-- <option value="main">main</option> -->
		</select>
	</div>
	<div class="form-group">
	    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id?>" >
		<button type="submit" class="btn btn-secondary float-right">Submit</button>
		<div class="clearfix"></div>
	</div>						
</form>
			
<div class="d-flex align-items-center" id="add-project-loadder" style="display: none !important">
  <strong id="text-message">Loading...</strong>
  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
</div>	

<div class="alert alert-danger" role="alert" id="add-project-error" style="display: none !important">
  A simple danger alert—check it out!
</div>


				
<script type="text/javascript">
$(function () {
    
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : 'server/php/';
    $('#project_photo').fileupload({
        url: '/project-owner/dashboard/ajaxUploadUserPhoto',
        dataType: 'json',
        done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        if (file.error){
                            alert(file.error+' - for uploaded photo');
                            var progress =0;
                            $('#img_project').hide();
                            $('#progress-project-photo .progress-bar').css(
                                'width',
                                progress + '%'
                            );
                            $('#progress-project-photo').attr('data-percent', progress + '%');
                            $('#artist_photo_url').val('');
                        }else {
                            $('#img_project').show();
                            $('#img_project').attr('src',file.url);
                            $('#progress-project-photo').fadeOut();
                        }
                    });
             
        },
        progressall: function (e, data) {
            $('#progress-project-photo').show();
            $('#img_project').hide();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress-project-photo .progress-bar').css(
                'width',
                progress + '%'
            );
            $('#progress-project-photo').attr('data-percent', progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});
</script>

