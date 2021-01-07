<link rel="stylesheet" href="/assets/css/progress-animation.css">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<style>
ul.ui-autocomplete {
    z-index: 1100;
}
</style>
<div class="progress-container" style="display:none;">
	<!-- progress 1-->
	<div class="progress-box" id="load_save_project" style="display: none">
		<span class="progress-title">Saving project</span>
		<span class="progress-title-completed">Saving project</span>
		<div class="progress-animation">
			<div class="bk d1"></div><div class="bk d2"></div><div class="bk d3"></div><div class="bk d4"></div><div class="bk d5"></div><div class="bk d6"></div>
		</div>
	</div>
	<!-- progress 2 -->
	<div class="progress-box" id="load_save_data" style="display: none">
		<span class="progress-title">Generating data contract</span>
		<span class="progress-title-completed">Generating data contract</span>
		<div class="progress-animation">
			<div class="bk d1"></div><div class="bk d2"></div><div class="bk d3"></div><div class="bk d4"></div><div class="bk d5"></div><div class="bk d6"></div>
		</div>
	</div>
	
	<div class="progress-box" id="load_save_dan" style="display: none">
		<span class="progress-title">Generating dan contract</span>
		<span class="progress-title-completed">Generating dan contract</span>
		<div class="progress-animation">
			<div class="bk d1"></div><div class="bk d2"></div><div class="bk d3"></div><div class="bk d4"></div><div class="bk d5"></div><div class="bk d6"></div>
		</div>
	</div>
	
	<div class="progress-box" id="load_save_esh" style="display: none">
		<span class="progress-title">Generating 1,000,000 SCESH to dan contract</span>
		<span class="progress-title-completed">Generating 1,000,000 SCESH to dan contract</span>
		<div class="progress-animation">
			<div class="bk d1"></div><div class="bk d2"></div><div class="bk d3"></div><div class="bk d4"></div><div class="bk d5"></div><div class="bk d6"></div>
		</div>
	</div>
	
	<div class="progress-box" id="load_save_status" style="display: none">
		<span class="progress-title">Saving status</span>
		<div class="progress-animation">
			<div class="bk d1"></div><div class="bk d2"></div><div class="bk d3"></div><div class="bk d4"></div><div class="bk d5"></div><div class="bk d6"></div>
		</div>
	</div>
	
</div>
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
	
	<div class="form-label-group flg mb-2 row">
		<div class="col-lg-12">
			<label for=""> Phone Number </label>
		</div>
		<div class="col-lg-6 mb-3">
			<select name="project_country" id="project_country" class="form-control custom-select">
				<option <?=empty($country_id)?'SELECTED':''?> value=""></option>
				<?php
					$selected_phone_code = '';
				?>
				<?php if ($countries->num_rows() > 0):?>
					<?php foreach ($countries->result() as $row):?>
					<option <?=$row->country_id==$country_id?'SELECTED':''?> value="<?php echo $row->country_id?>" data="<?php echo $row->name?>"><?php echo $row->code.' (+'.$row->phone_code.')'?></option>
					<?php 
						endforeach;?>
				<?php endif?>
			</select>
		</div> 
		<div class="col-lg-6">
			<div class="input-group-prepend">
				<input type="text" name="project_phone_number" id="project_phone_number" class="form-control" placeholder="phone number" value="<?=$phone_number?>"> 
			</div>
		</div>
		<span class="txt-alert" style="display:none;">Invalid Country Code</span> 
	</div>
											<div class="form-label-group flg mb-2">
												<div class="row">
													<div class="col-lg-3 mb-3">
														<label for="project_state" class="col-sm-2 col-form-label">State</label>
														<input type="text" class="form-control" id="project_state" placeholder="State"  value="<?=$state?>">
													</div>
													<div class="col-lg-3 mb-3">
														<label for="inputPassword" class="col-sm-2 col-form-label">City</label>
														<input type="text" class="form-control" id="project_city" placeholder="City"  value="<?=$city?>">
													</div>
													<div class="col-lg-3 mb-3">
														<label for="inputPassword" class="col-sm-2 col-form-label">Address</label>
														<input type="text" class="form-control" id="project_address" placeholder="Address"  value="<?=$address?>">
													</div>
													<div class="col-lg-3 mb-3">
														<label for="inputPassword" class="col-sm-2 col-form-label">Zipcode</label>
														<input type="text" class="form-control" id="project_zipcode" placeholder="Zipcode"  value="<?=$zipcode?>">
													</div>
												</div>
												<span class="txt-alert-location" style="display:none;"></span> 
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
	
	<div class="form-group" id="">
		<label>Upload project cover image</label>
		<input type="file" class="form-control-file" id="project_photo_cover">
		<span class="txt-alert" style="display:none">Please upload an image!</span>
		 <div class="progress pos-rel col-xs-10 col-sm-5" id="progress-project-photo-cover" style="display:none">
													<div class="progress-bar"></div>
                     	</div>
         <?php if ($cover_image != ""):?>
            <img src="<?php echo $cover_image?>" class="img-fluid" id="img_project_cover" >
         	<?php else:?>
         	<img src="" class="img-fluid" id="img_project_cover" style="display: none">
         <?php endif?>            	
         
	</div>
	
	<div class="form-group" id="" <?php if ($project_id>0):?> style="display:none"<?php endif?>>
		<label>Network</label>
		<select  class="form-control" id="project_network">
				<option value="test" <?php if ($network == 'test') echo 'selected'?>>test</option>
				<?php
					if($upgrade_plan){
				?>
					<option value="main" <?php if ($network == 'main') echo 'selected'?>>main</option>
				<?php
					}
				?>
		</select>
	</div>
	<div class="form-group">
	    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id?>" >
		<button type="submit" class="btn btn-secondary float-right test">Submit</button>
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
$(document).ready(function() {
	$( document ).on( "change", "#project_country", function() {
		$('#project_state').attr('disabled',true);
        var c_code = $(this).val();
        if (c_code == '1'){
            $('#project_state').attr('disabled',false);
        }else {
        	 $('#project_state').attr('disabled',true);
        }
    });

	 $( "#project_state" ).autocomplete({
			source: "/project-owner/myprojects/autosearchstate",
			minLength: 2,
			select: function( event, ui ) {
			var val = ui.item.value;
		        jQuery('#project_state').val();
          	}
		});

	 $( "#project_city" ).autocomplete({
	        source: function (request, response) {
	        	 var country = $("#project_country option:selected").attr('data');
	        $.ajax({
	        type: "POST",
	        url:"/project-owner/myprojects/autosearchcity",
	        data: {term:request.term,country:country},
	        success: response,
	        dataType: 'json',
	        minLength: 2,
	        delay: 100
	            });
	        }});
     
	

	 
});

$(function () {

	$('#project_desc').trumbowyg();
    
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : 'server/php/';
    $('#project_photo').fileupload({
        url: '/project-owner/myprojects/ajaxUploadUserPhoto',
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

$(function () {
    
    'use strict';
    // Change this to the location of your server-side upload handler:
    var url = window.location.hostname === 'blueimp.github.io' ?
                '//jquery-file-upload.appspot.com/' : 'server/php/';
    $('#project_photo_cover').fileupload({
        url: '/project-owner/myprojects/ajaxUploadUserPhoto',
        dataType: 'json',
        done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        if (file.error){
                            alert(file.error+' - for uploaded photo');
                            var progress =0;
                            $('#img_project_cover').hide();
                            $('#progress-project-photo-cover .progress-bar').css(
                                'width',
                                progress + '%'
                            );
                            $('#progress-project-photo-cover').attr('data-percent', progress + '%');
                            
                        }else {
                            $('#img_project_cover').show();
                            $('#img_project_cover').attr('src',file.url);
                            $('#progress-project-photo-cover').fadeOut();
                        }
                    });
             
        },
        progressall: function (e, data) {
            $('#progress-project-photo-cover').show();
            $('#img_project_cover').hide();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress-project-photo-cover .progress-bar').css(
                'width',
                progress + '%'
            );
            $('#progress-project-photo-cover').attr('data-percent', progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});


</script>

