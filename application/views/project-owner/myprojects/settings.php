<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<style>
.nav-pills .nav-link.active, .nav-pills .show > .nav-link {
    color: #fff;
    background-color: #F9B971;
}
</style>
<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Settings</li>
		</ol>
	</div>
<?php
	if(!empty($security)){
		$security = 'active';
		$security_show = 'active show';
		$you = '';
		$you_show = '';
	}else{
		$you = 'active';
		$you_show = 'active show';
		$security = '';
		$security_show = '';
	}
?>

	<div class="page-container profile-height">
		<!-- -->
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="card newsfeed-card mb-3 ">
						<div class="card-body">
							<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
								<a class="nav-link <?=$you?>" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-you" role="tab"> <i class="fas fa-user-circle mr-2" aria-hidden="true"></i> You </a>
								<a class="nav-link <?=$security?>" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-security" role="tab"> <i class="fas fa-lock mr-2" aria-hidden="true"></i> Security </a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-8">
					<div class="card newsfeed-card mb-3 ">
						<div class="card-body">
							<div class="tab-content" id="v-pills-tabContent">
								<div class="tab-pane fade show <?=$you_show?>" id="v-pills-you" role="tabpanel">
									<div class="row">
										<div class="col-md-12">
											<h4>
												You
											</h4> 
											<hr>
											<div class="form-group">
												<label for=""> First Name </label>
												<input type="text" class="form-control" id="first_name" value="<?=$member['firstname']?>"> 
											</div>
											<div class="form-group">
												<label for=""> Last Name </label>
												<input type="text" class="form-control" id="last_name" value="<?=$member['lastname']?>"> 
											</div>
											<div class="form-label-group">
												<label for=""> Username </label>
												<input disabled type="text" class="form-control" id="username" value="<?=$member['username']?>"> 
											</div>
											
											<div class="form-label-group flg mb-2">
											<label for=""> Phone Number </label>
												<div class="input-group mb-3">
													<div class="row no-gutters" style="width:100%;">
														<div class="col">
															<select name="owner_country" id="owner_country" class="form-control custom-select">
																<option <?=empty($member['country_id'])?'SELECTED':''?> value=""></option>
																<?php
																	$selected_phone_code = '';
																?>
																<?php if ($countries->num_rows() > 0):?>
																	<?php foreach ($countries->result() as $row):?>
																	<option <?=$row->country_id==$member['country_id']?'SELECTED':''?> value="<?php echo $row->country_id?>"><?php echo $row->code.' (+'.$row->phone_code.')'?></option>
																	<?php 
																		endforeach;?>
																<?php endif?>
															</select>
														</div>
														<div class="col-md-9">
															<div class="input-group-prepend">
																<input type="text" name="phone_number" id="phone_number" class="form-control" placeholder="phone number" value="<?=$member['phone_number']?>"> 
															</div>
														</div>
													</div>
												</div> 
												<span class="txt-alert" style="display:none;">Invalid Country Code</span> 
											</div>
											
											<div class="form-label-group">
												<label for="profileuploads"> Upload Profile Image </label>
												<div class="mb-3">
													<input type="file" class="form-control-file" id="profileupload"> </div>
												<div class="mb-3">
													<div class="progress" id="progress_profile" style="display: none">
														<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
													</div>
												</div>
												<div class="mb-3">
													<div class="preview-image-upload"> <img id="profile_photo_file" class="img-thumbnail" src="<?=empty($member['profile_image'])?'http://cdn.vnoc.com/servicechain/user-default.png':$member['profile_image']?>" style="" width="128"> </div>
												</div> 
												<small class="form-text text-muted">* Note: Image size 150x150.</small> 
											</div>
											<div class="form-group">
												<a href="javascript:;" class="btn btn-secondary" id="profile_save"> <i class="fas fa-check" aria-hidden="true"></i> Save changes </a>
											</div>
										</div>
										<div class="col-md-6 d-none">
											<h4>
												Social
											</h4> 
											<small class="d-block">
												Show off some social flair in your profile!
											</small>
											<hr>
											<div class="form-group">
												<label for="">Facebook Url</label>
												<input type="text" class="form-control" id="facebook" value=""> </div>
											<div class="form-group">
												<label for="">Twitter Url</label>
												<input type="text" class="form-control" id="twitter" value=""> </div>
											<div class="form-group">
												<label for="">Instagram Url</label>
												<input type="text" class="form-control" id="instagram" value=""> </div>
											<div class="form-group">
												<label for="">Github Url</label>
												<input type="text" class="form-control" id="github" value=""> </div>
											<div class="form-group">
												<label for="">Skype Username</label>
												<input type="text" class="form-control" id="skype_username" value=""> </div>
										</div>										
									</div>
								</div>
								<div class="tab-pane fade <?=$security_show?>" id="v-pills-security" role="tabpanel">
									<div class="row">
										<?php
											if(empty($member['has_request']) && $member['signup_from']!='servicechain'){
										?>
										<div class="col-md-6">
											<h4>
												Change your password
											</h4>
											<hr>

											<div class="form-group">
												<a href="javascript:;" class="btn btn-secondary" id="request_temp_pass"> <i class="fas fa-check" aria-hidden="true"></i> Request Temporary Password </a>
											</div>
										</div>
										<?php
											}else{
										?>
										<div class="col-md-6">
											<h4>
												Change your password
											</h4>
											<hr>
											<div class="form-group">
												<label for=""> Old password </label>
												<input type="password" id="oldpassword" class="form-control"> </div>
											<div class="form-group">
												<label for=""> New password </label>
												<input type="password" id="newpassword" class="form-control"> </div>
											<div class="form-group">
												<a href="javascript:;" class="btn btn-secondary" id="password_save"> <i class="fas fa-check" aria-hidden="true"></i> Change password </a>
											</div>
											
										</div>
										<?php
											}
										?>
										<div class="col-md-6">
											<h4>
												Delete Account
											</h4>
											<hr>
											<a href="javascript:;" class="btn btn-outline-danger btn-block btn-lg" id="delete_account"> <i class="fas fa-user-circle" aria-hidden="true"></i> Delete Account </a>
											
										</div>
									</div>
									
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- -->
	</div>	
<script>
$( document ).ready(function() {
	$('#request_temp_pass').click(function(){
		$(this).html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');
		$.post('/project-owner/settings/requestpassword',function(data){
			if(data.status){
				$('#request_temp_pass').html('<i class="fas fa-check"></i> Request Temporary Password ');

				Swal.fire({
						type: 'success',
						title:'Success',
						text: 'Please check your email for your temporary password',
						showConfirmButton: false,
						timer: 2500
					});
			}else{
				Swal.fire({
						type: 'error',
						title: 'Request Temporary Password',
						text: 'an error occurred while processing your request. Please try again.',
					});
			}
		});
	});
	
	$('#profile_save').click(function(){

		var profile_image = $('#profile_photo_file').attr('src');
		var first_name = $('#first_name').val();
		var last_name = $('#last_name').val();
		var tag_line = $('#tag_line').val();
		var twitter = $('#twitter').val();
		var instagram = $('#instagram').val();
		var github = $('#github').val();
		var skype_username = $('#skype_username').val();
		var facebook = $('#facebook').val();
		var owner_country = $('#owner_country').val();
		var phone_number = $('#phone_number').val();

		$(this).html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');
		$.post('/project-owner/settings/ajaxSaveSettings',{
			profile_image:profile_image,
			first_name:first_name,
			last_name:last_name,
			country_id:owner_country,
			phone_number:phone_number,
			tag_line:tag_line,
			twitter:twitter,
			instagram:instagram,
			github:github,
			skype_username:skype_username,
			facebook:facebook
		},function(data){
			if(data.status){
				$('.img-user').attr('src',data.profile_image);
				$('#profile_save').html('<i class="fas fa-check"></i> Save changes');

				Swal.fire({
						type: 'success',
						title:'Success',
						text: 'Details has been saved successfully',
						showConfirmButton: false,
						timer: 1500
					});
			}
		});
		

	});
	
	$('#newpassword').keypress(function(e) {
		if(e.which == 13) {
			$('#password_save').trigger('click');
		}
	});
	
	$('#password_save').click(function(){
		var oldpass = $('#oldpassword').val();
		var newpass = $('#newpassword').val();

		$(this).html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');

		if(newpass.length < 5) {
			$('#password_save').html('<i class="fas fa-check"></i> Change Password');
			Swal.fire({
					type: 'error',
					title: 'Password Error',
					text: 'Password must be atleast 5 characters long!',
				});

		}else{
			$.post('/project-owner/settings/checkoldpass',{
				oldpass:oldpass
			},function(data){
				if(data.status){
					$.post('/project-owner/settings/savepassword',{
						newpass:newpass,
						oldpass:oldpass
					},function(data){
						$('#password_save').html('<i class="fas fa-check"></i> Change Password');
						
						Swal.fire({
							type: 'success',
							title:'Success',
							text: 'Password has been changed successfully',
							showConfirmButton: false,
							timer: 1500
						});
					});
				}else{
					$('#password_save').html('<i class="fas fa-check"></i> Change Password');
					Swal.fire({
						type: 'error',
						title: 'Password Error',
						text: 'Your old password seems incorrect. Please try again.',
					});
				}
			});
		}

	});
	
	$('#delete_account').click(function(){
		Swal.fire({
			title: 'Enter Your Password to Confirm',
			input: 'password',
			inputAttributes: {
				autocapitalize: 'off'
			},
			showCancelButton: true,
			confirmButtonText: 'Delete Account',
			showLoaderOnConfirm: true,
			preConfirm: (password) => {
				$('#delete_account').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Deleting..');
				$.post('/project-owner/settings/delete',{
					password:password},function(data){
						if(data.status){
								let timerInterval
								Swal.fire({
									title: 'Logging Out Please Wait..',
									html: '',
									timer: 1000,
									onBeforeOpen: () => {
										Swal.showLoading()

									},
									onClose: () => {
										clearInterval(timerInterval)
									}
								}).then((result) => {
									if (

										result.dismiss === Swal.DismissReason.timer
										) {
										window.location = '/project-owner/logout';
								}
							})
						}else{
							$('#delete_account').html('<i class="fas fa-user-circle"></i>Delete Account');
							Swal.fire({
								type: 'error',
								title: 'Password Error',
								text: 'Password is Incorrect',
							});
						}
					});
			},
			allowOutsideClick: () => !Swal.isLoading()
		})
	});
});
$(function () {
	'use strict';
	$('#profileupload').fileupload({
        url: '/project-owner/settings/uploadphoto',
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
					$('#profile_photo_file').val('');
				}else {
					$('#profile_photo_file').attr('src',file.url);
					$('#profile_photo_file').show();
					$('#profile').val(file.name);
				}
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
<?php $this->load->view('project-owner/dashboard/footer');?>


	
	