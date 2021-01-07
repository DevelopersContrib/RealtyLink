<?php $this->load->view('home/header');?>
<style>
body {
	background: #FAF6F2;
	position: relative;
	padding: 140px 0px 20px;
	overflow-x: hidden;
}
.form-signup {
	margin-bottom: 120px !important;
}
.form-signin {
	width: 100%;
	max-width: 720px;
	margin: auto;
}

.form-inner {
        background: #fff;        
        border-radius: 8px;
	box-shadow: 0 6px 15px rgba(0, 0, 0, 0.16);
	padding: 20px;
}
.s-form-inner {
	padding: 85px 30px 85px 15px;
}
.form-inner .h2 {
        font-weight: 800;
	  font-size: 1.5rem;
}

.form-label-group {
	position: relative;
	margin-bottom: 1rem;
}

.form-label-group > input,
.form-label-group > label {
	height: 3rem;
	padding: .75rem;
}

.form-label-group > label {
	position: absolute;
	top: 0;
	left: 0;
	display: block;
	width: 100%;
	margin-bottom: 0; 
	line-height: 1.5;
	color: #495057;
	pointer-events: none;
	cursor: text; 
	border: 1px solid transparent;
	border-radius: .25rem;
	transition: all .1s ease-in-out;
}

.form-label-group input::-webkit-input-placeholder {
	color: transparent;
}

.form-label-group input:-ms-input-placeholder {
	color: transparent;
}

.form-label-group input::-ms-input-placeholder {
	color: transparent;
}

.form-label-group input::-moz-placeholder {
	color: transparent;
}

.form-label-group input::placeholder {
	color: transparent;
}

.form-label-group input:not(:placeholder-shown) {
	padding-top: 1.25rem;
	padding-bottom: .25rem;
}

.form-label-group input:not(:placeholder-shown) ~ label {
	padding-top: .25rem;
	padding-bottom: .25rem;
	font-size: 12px;
	color: #777;
}

@supports (-ms-ime-align: auto) {
	.form-label-group > label {
		display: none;
	}
	.form-label-group input::-ms-input-placeholder {
		color: #777;
	}
}

@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
	.form-label-group > label {
		display: none;
	}
	.form-label-group input:-ms-input-placeholder {
		color: #777;
	}
}
.form-control {
	background-color: #fdfcfc;
}

.txt-alert {
        position: absolute;
        font-size: 12px;
        color: red;
        bottom: 50px;
        background: #fff;
        padding: 0px 3px 0px 1px;
}
.flg select, .flg input {
	height: 3.125rem;
	padding: .75rem;
}
.flg input {
	border-top-left-radius: 0px;
	border-bottom-left-radius: 0px;
}
footer {
	display: none;
}
/* Note Style ---------- */
.note {
    color: #666;
    position: relative;
    display: block;
    margin-bottom: 1rem
}
.note-header {
    padding: .25rem 1rem;
    color: #555;
}
.note-body {
    padding: 1rem;
}
.note-info {
    background-color: #FAF6F2;
}
.note-info .note-header {
    background-color: #D4EDDA;
}
.note-danger {
    background-color: #FAE2E2;
}
.note-danger .note-header {
    background-color: rgba(217,83,79,.8);
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="form-inner">
		<div class="text-center mb-2 mt-3">
			<h1 class="h2 mb-3"><i class="fas fa-unlock-alt"></i>&nbsp;Verify New Password</h1>
		</div>
		<div class="note note-info" style="display:none;">
			<div class="note-header">
			    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
			    Success
			</div>
			<div class="note-body">
			    Resend Verification
			</div>
        </div>
        <div class="note note-danger" style="display:none;">
			<div class="note-header">
			    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
			    Error
			</div>
			<div class="note-body">
			    Resend Verification
			</div>
        </div>
                <div class="form-label-group">                    
                    <input type="text" class="form-control" id="inputEmail" value="<? if(isset($_GET['em']) && !empty($_GET['em'])) echo base64_decode($_GET['em']); ?>"  placeholder="" readonly>
		            <label for="inputEmail">Email</label>
                    <small><span class="txt-alert error-email text-danger" style="display:none;">This is an error message</span></small>
                </div>
                <div class="form-label-group">                   
                    <input type="password" class="form-control" id="inputNewPassword" placeholder="New Password">
		       <label for="inputNewPassword">New Password</label>
                    <small><span class="txt-alert error-new-password text-danger" style="display:none;">This is an error message</span></small>
                </div>
                <div class="form-label-group">                   
                    <input type="password" class="form-control" id="inputConfirmPassword" placeholder="Confirm Password">
		       <label for="inputConfirmPassword">Confirm Password</label>
                    <small><span class="txt-alert error-confirm-password text-danger" style="display:none;">This is an error message</span></small>
                     <div class="clearfix"></div>
			   <a href="/" class="float-left mt-4"><img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" width="120"></a>
			  <div class="float-right">
				<a href="/login" class="btn text-secondary mt-3"><small>Back to login</small></a>
				 <button class="btn btn-secondary mt-3" id="btnChangePassword">Submit</button>				
			  </div>
			   <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#btnChangePassword').on('click',function() {
        changePassword();
    });

    $('#inputConfirmPassword').keypress(function(e) {
        if(e.which == 13) {
            changePassword();
        }
    });

    var validatePassword = function(newPassword,password) {
        let returnValue = false;

        $('.text-alert').hide();

        if(!newPassword) {
            $('.error-new-password').text('Please enter your new password.')
                .show();
        } else if(newPassword.length < 5) {
            $('.error-new-password').text('Your password must be atleast 5 characters long.')
                .show();
        } else if(!password) {
            $('.error-confirm-password').text('Please confirm your password.')
                .show();
        } else if(newPassword !== password) {
            $('.error-confirm-password').text('Passwords does not matched.')
                .show();
        } else {
            returnValue = true;
        }

        return returnValue;
    }

    var changePassword = function() {
        let email = $('#inputEmail').val();
        let newPassword = $('#inputNewPassword').val();
        let password = $('#inputConfirmPassword').val();

        if(validatePassword(newPassword,password)) {
            $.ajax({
                url: '/settings/changepassword',
                method: 'POST',
                data: { email:email,password:password },
                beforeSend: function() {
                    $('#btnChangePassword').html('<i class="fa fa-spin fa-spinner"></i> Saving...');
                },
                success: function(data) {
                    if(!data.status) {
                        $('.note-danger').show()
                            .find('.note-body')
                                .html('Unable to change your password. Please try again!');
                        return false;
                    }

                    $('.note-info').show()
                        .find('.note-body')
                            .html('You have succesfully changed your password. Please click the <a href="/login">link</a> to login.');
                    $('#inputNewPassword').val('');
                    $('#inputConfirmPassword').val('');
                },
                complete: function() {
                    $('#btnChangePassword').html('Submit');
                },
            });
        }
    }
});
</script>