<style>
.upload-image-input {
	  width:90px;
	color:transparent;
}
</style>
<form id="addtaskform">
	<div class="form-group">
		<label>Title</label>
		<input type="text" class="form-control txt" placeholder="Finish roofing eg." id="title" name="title" required> 
	</div>
	<div class="form-group">
		<label>Description</label>
		<textarea class="form-control txt" aria-label="With textarea" placeholder="Finish the roofing on the patio  eg." id="description" name="description"></textarea>
	</div><?php /*?>
	<div class="form-group">
		<label>Type</label>
		<select class="form-control" id="project_type" name="project_type">
			<option>...</option>
		</select>
	</div><?php */?>
	<div class="form-group">
		<label>Payment</label>
		<select class="form-control" id="payment" name="payment">
			<option value="equity">ESH</option>
			<option value="cash">Cash</option>
			<option value="cash/equity">Cash and ESH</option>
		</select>
	</div>
	<div class="form-group esh_value values">
		<label>ESH Value</label>
		<input type="number" class="form-control txt" id="esh_value" name="esh_value" required> 
	</div>
	<div class="form-group cash_value values" style="display:none">
		<label>Cash Value</label>
		<input type="number" class="form-control txt" id="cash_value" name="cash_value" > 
	</div>
	
	<div class="form-group">
		<label>Goal Date</label>
		<input type="date" class="form-control txt" id="goal_date" name="goal_date" required> 
	</div>	
	<div class="form-group">
		<div class="row">
			<div class="col-md-6">
				<label>Legal File</label>	
				<input type="file" class="form-control-file" id="legalfileupload" >
				<div class="mb-3">
					<div class="progress" id="progress_profile" style="display: none">
						<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<div id="legalfile">
						<ul>
							
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<label for="profileuploads"> Upload Profile Image </label>
				<div class="mb-3">
					<input type="file" class="form-control-file" id="profileupload"> </div>
				<div class="mb-3">
					<div class="progress" id="task_image" style="display: none">
						<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
				<div class="mb-3">
					<div class="preview-image-upload"> <img id="profile_photo_file" class="img-thumbnail" src="<?=!empty($task['image']) ? $task['image']:'https://cdn.vnoc.com/servicechain/default-image-photo.png'?>" style="width:128px; opacity:.5;"> </div>
				</div>
				<input type="hidden" id="task_img" name="task_img" value="<?if(!empty($task['image'])) echo $task['image']?>">
				<!-- <small class="form-text text-muted">* Note: Image size 150x150.</small> --> 
			</div>
		</div>		
	</div>	
	<div class="form-group">
		<label>Verification</label>
		<textarea class="form-control txt" aria-label="With textarea" id="verification" name="verification">I will verify this task through photos and videos of the completed service.</textarea>
	</div>
	<div class="form-group">
		<button id="btnSubmit" type="submit" class="btn btn-secondary float-right">Submit</button>
		<input type="hidden" value="<?=$project_id?>" name="project"/>
		<input type="hidden" value="" class="txt" name="task" id="task" />
		<input type="hidden" value="" class="txt" name="container" id="container" />
		<div class="clearfix"></div>
	</div>						
</form>
<script>
$( document ).ready(function() {
	$('#description').trumbowyg();
	$('#payment').change(function(){
		$('#esh_value').removeAttr('required');
		$('#cash_value').removeAttr('required');
		$('.values').hide();
		if($(this).val()=='cash'){
			$('#cash_value').attr('required', 'required');
			$('.cash_value').show();
		}else if($(this).val()=='cash/equity'){
			$('#esh_value').attr('required', 'required');
			$('#cash_value').attr('required', 'required');
			$('.values').show();
		}else{
			$('#esh_value').attr('required', 'required');
			$('.esh_value').show();
		}
	});
});
</script>
<script>
window.pressed = function(){
    var a = document.getElementById('imageupload');
    if(a.value == "")
    {
        fileLabel.innerHTML = "Choose file";
    }
    else
    {
        var theSplit = a.value.split('\\');
        fileLabel.innerHTML = theSplit[theSplit.length-1];
    }
};
</script>

