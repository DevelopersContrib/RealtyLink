<form class="addprojectform" style="display: none">
	<div class="form-group">
		<label>Project Title</label>
		<input type="text" class="form-control" id="project_title" value="<?php echo $title?>"> 
		<span class="txt-alert" style="display:none">Please enter project title!</span>
	</div>
	<div class="form-group" id="" >
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
			
<div class="d-flex align-items-center" id="add-project-loadder" style="">
  <strong id="text-message"><?php echo $message?></strong>
  <div class="spinner-border ml-auto" role="status" aria-hidden="true"></div>
</div>	

<div class="alert alert-danger" role="alert" id="add-project-error" style="display: none !important">
  A simple danger alert—check it out!
</div>


				


