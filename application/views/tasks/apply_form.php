<form id="addtaskform" action="javascript:processApplication();" >
	<div class="alert alert-danger" role="alert" id="appError" style="display: none">
        This is a danger alert—check it out!
    </div>

	<div class="form-group">
		<label>Message</label>
		<textarea class="form-control" aria-label="With textarea" id="apply_message" name="apply_message"></textarea>
	</div>
	
	<div class="form-group">
		<button id="btnSubmitApp" type="submit" class="btn btn-primary float-right" >Submit Application</button>
		<div class="clearfix"></div>
	</div>						
</form>

