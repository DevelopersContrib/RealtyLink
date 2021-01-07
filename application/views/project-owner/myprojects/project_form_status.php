<form class="addprojectform" action="javascript:processStatus();">
	
	<div class="form-group" >
		<label>Status</label>
		<select  class="form-control" id="project_status">
				<option value="new" <?php if ($status=='new') echo 'selected'?>>new</option>
				<option value="in progress" <?php if ($status=='in progress') echo 'selected'?>>in progress</option>
				<option value="completed" <?php if ($status=='completed') echo 'selected'?>>completed</option>
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