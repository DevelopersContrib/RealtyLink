<div class="row">
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <label for=""> License Number</label>
            <input type="text" class="form-control" id="license_num" value="<?if(!empty($licenseDetails->row()->license_no)) echo $licenseDetails->row()->license_no?>">
            <strong><small><span class="text-danger license-error error-licenseNum" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Status </label>
            <input type="text" class="form-control" id="license_status" value="<?if(!empty($licenseDetails->row()->status)) echo $licenseDetails->row()->status?>">
            <strong><small><span class="text-danger license-error error-licenseStatus" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Type </label>
            <input type="text" class="form-control" id="license_type" value="<?if(!empty($licenseDetails->row()->type)) echo $licenseDetails->row()->type?>">
            <strong><small><span class="text-danger license-error error-licenseType" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Date Issued </label>
            <input type="text" class="form-control" id="license_date" value="<?if(!empty($licenseDetails->row()->date_issued)) echo $licenseDetails->row()->date_issued?>" readonly>
            <strong><small><span class="text-danger license-error error-licenseDate" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> License Info </label>
            <textarea class="form-control" name="" id="license_info" rows="5"><?if(!empty($licenseDetails->row()->info)) echo $licenseDetails->row()->info?></textarea>
            <strong><small><span class="text-danger license-error error-licenseInfo" style="display:none;"></span></small></strong>
        </div>
        <div class="form-group mt-3">
            <a href="javascript:;" class="btn btn-secondary" id="btn_save_license_details"> <i class="fas fa-check" aria-hidden="true"></i> Save </a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#license_date').datepicker({
		uiLibrary: 'bootstrap4'
	});

    $('#btn_save_license_details').on('click',function() {
		let num = $('#license_num').val();
		let status = $('#license_status').val();
		let type = $('#license_type').val();
		let date = $('#license_date').val();
		let info = $('#license_info').val();

		if(validateLicenseDetails(num,status,type,date,info)) {
			$.ajax({
				url: '/settings/updatelicensedetails',
				method: 'POST',
				data: { num:num,status:status,type:type,date:date,info:info },
				beforeSend: function() {
					$('#btn_save_license_details').html('<i class="fa fa-spin fa-spinner"></i> Saving...');
				},
				success: function(data) {
					let title = 'Saved'
					let message = 'License details has successfully updated';
					let type = 'success';
					
					if(data.status == '0' || data.status == '') {
						title = 'Error'
						message = 'An error occured during the saving. Please try again!';
						type = 'error';
					};

					Swal.fire(
						title,
						message,
						type
					);				
				},
				complete: function() {
					$('#btn_save_license_details').html('<i class="fas fa-check" aria-hidden="true"></i> Save')
				},
			});
		};
	});

	var validateLicenseDetails = function(licenseNum,licenseStatus,licenseType,licenseDate,licenseInfo) {
		let value = false;

		$('.license-error').hide();

		if(!licenseNum) {
			$('.error-licenseNum').html('Please enter your license number.')
				.show();
		} else if(!licenseStatus) {
			$('.error-licenseStatus').html('Please enter your license status.')
				.show();
		} else if(!licenseType) {
			$('.error-licenseType').html('Please enter your license type.')
				.show();
		} else if(!licenseDate) {
			$('.error-licenseDate').html('Please enter your license issued date.')
				.show();
		} else if(!licenseInfo) {
			$('.error-licenseInfo').html('Please enter your license info.')
				.show();
		} else {
			value = true;
		}

		return value;
	}
</script>