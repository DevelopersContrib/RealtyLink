<div class="row">
	<div class="col-md-12">
		<h4>Where do you want to receive notifications?</h4> 
		<div class="col-md-6">
    		<div class="form-group">
    	 			<select  class="form-control" id="receive_notification">
    				    <option value="email" <?php if ($member['notify_by']=='email') echo 'selected'?>>email</option>
    				    <option value="sms" <?php if ($member['notify_by']=='sms') echo 'selected'?>>sms</option>
    				</select>
    	     </div>
    	     <div class="form-group">
				<a href="javascript:;" class="btn btn-secondary" id="saveRnotify"> <i class="fas fa-check" aria-hidden="true"></i> Save </a>
			</div>
	     </div>
	</div>
</div>

<script>
$( document ).ready(function() {
	$('#saveRnotify').click(function(){

		var notifiy = $('#receive_notification').val();
		$(this).html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i> Saving...</span>');
		$.post('/settings/ajaxSaveNotify',{
			notifiy:notifiy
		},function(data){
			if(data.status){
				Swal.fire({
					type: 'success',
					title:'Success',
					text: 'Details has been saved successfully',
					showConfirmButton: false,
					timer: 3000
				});
			} else {
				Swal.fire({
					title: 'Error',
					text: data.message,
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Setup'
				}).then((result) => {
					if (result.value) {
						$('.nav-link').removeClass('active');
						$('#v-pills-home-tab').addClass('active');
						$('.tab-pane').removeClass('active');
						$('#v-pills-you').addClass('show active');
						$('#phone_number').focus();
					}
				})
			}
			$('#saveRnotify').html('<i class="fas fa-check" aria-hidden="true"></i> Save');
		});
		

	});
});
</script>