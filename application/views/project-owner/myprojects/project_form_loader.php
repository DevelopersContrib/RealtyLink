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

<div class="alert alert-danger" role="alert" id="add-project-error" style="display: none !important">
  A simple danger alert—check it out!
</div>


				


