validateForm = function() {
		var firstname = $('#owner_firstname').val();
		var lastname = $('#owner_lastname').val();
	    var userName = $('#owner_username').val();
		var email = $('#owner_email').val();
		var password = $('#owner_password').val();
		var confirmPassword = $('#owner_password2').val();
		var country = $('#owner_country').val();
		var phone = $('#owner_phone').val();

		$('.txt-alert').hide();
		
		
		if(!firstname) {
			$('#owner_firstname').focus();
			$('#owner_firstname').siblings('.txt-alert')
				.html('Please enter your first name!')
					.show();
		} else if(!lastname) {
			$('#owner_lastname').focus();
			$('#owner_lastname').siblings('.txt-alert')
				.html('Please enter your last name!')
					.show();
		} else if(!userName) {
			$('#owner_username').focus();
			$('#owner_username').siblings('.txt-alert')
				.html('Please enter your username!')
					.show();
		} else if(userName && userName.length < 5) {
			$('#owner_username').focus();
			$('#owner_username').siblings('.txt-alert')
				.html('Username must be atleast 5 characters long!')
					.show();
		} else if(!email) {
			$('#owner_email').focus();
			$('#owner_email').siblings('.txt-alert')
				.html('Please enter your email address!')
					.show();
		} else if(!validateEmailAddress(email)) {
			$('#owner_email').focus();
			$('#owner_email').siblings('.txt-alert')
				.html('Please enter a valid email address!')
					.show();
		} else if(!password) {
			$('#owner_password').focus();
			$('#owner_password').siblings('.txt-alert')
				.html('Please enter your password!')
					.show();
		} else if(password.length < 5) {
			$('#owner_password').focus();
			$('#owner_password').siblings('.txt-alert')
				.html('Password must be atleast 5 characters long!')
					.show();
		} else if(!confirmPassword) {
			$('#owner_password2').focus();
			$('#owner_password2').siblings('.txt-alert')
				.html('Please confirm your password!')
					.show();
		} else if(password !== confirmPassword) {
			$('#owner_password2').focus();
			$('#owner_password2').siblings('.txt-alert')
				.html('Password does not match!')
					.show();
		}  else if(!country) {
			alert("Please select phone contry code");
			$('#owner_country').siblings('.txt-alert')
			.html('Please select phone number code!')
				.show();
	   } else if (!phone){
		   alert("Please enter phone number");
		   $('#owner_phone').focus();
		   $('#owner_phone').siblings('.txt-alert')
			.html('Please enter phone number!')
				.show();
	   }else {
			register();
		}
	}

validateEmailAddress = function(email){
	var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	
	return regex.test(email);
}

register = function() {
	var firstname = $('#owner_firstname').val();
	var lastname = $('#owner_lastname').val();
    var userName = $('#owner_username').val();
	var email = $('#owner_email').val();
	var password = $('#owner_password').val();
	var country = $('#owner_country').val();
	var phone = $('#owner_phone').val();

	$.ajax({
		url: '/project-owner/signup/savemember',
		method: 'POST',
		data: { 
				firstname:firstname,
				lastname:lastname,
			    username:userName,
				email:email,
				password:password,
				country:country,
				phone:phone
				
			},
		beforeSend: function() {
			$('#btnCreateAccount').html('<i class="fas fa-spinner fa-spin"></i>  We are now creating your account');
		},
		success: function(data) {
			if(!data.status) {
				
				if(data.field == 'username' || data.field == 'email'){
					var errorContainer = null;

					errorContainer = data.message.indexOf('username') > -1 ? $('#owner_username'):$('#owner_email');

					errorContainer.siblings('.txt-alert')
					.html(data.message)
					.show();
				}else{
					$('#owner_phone').siblings('.txt-alert')
					.html('Please enter a valid phone number!')
					.show();
				}
			
				return false;
			}
			
			//var html = '<div class="text-center"><h3><p><i class="far fa-check-circle"></i></p> Thank you for signing up!<br> The activation email is on the way, <br>it was sent to<br> '+data.email+'<br> If it takes too long to arrive, please click resend button below<p class="mt-3"> <a href="javascript:resendCode();" class="btn btn-primary" id="btnResend">Resend</a></p></h3></div>';
$('.form-inner').hide();
$('#email_return').html(data.email);
//$('.success-container-alert').html(html)
//	.show();
$('.success-container-alert').removeClass('d-none');
			
			
		},
		complete: function() {
			$('#btnCreateAccount').html('Create Account');
		},
	});
}

function resendCode(){
	var email = $('#owner_email').val();
	$.ajax({
		url: '/project-owner/signup/resendcode',
		method: 'POST',
		data: { 
				email:email
			},
		beforeSend: function() {
			$('#btnResend').html('<i class="fas fa-spinner fa-spin"></i> Sending');
		},
		success: function(data) {
				$('#message_corner').html(data.message);
		},
		complete: function() {
			$('#btnResend').html('Resend');
		},
	});
}