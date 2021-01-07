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
	margin-bottom: .8rem;
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
        bottom: 45px;
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
			<h1 class="h2 mb-3"><i class="fas fa-unlock-alt"></i>&nbsp;Forgot Password</h1>
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
		<div class="form-label-group">
			   <input type="text" id="inputEmail" class="form-control" placeholder="Email address">
			   <label for="inputEmail">Email</label>
			   <small><span class="txt-alert error-email text-danger" style="display:none;">This is an error message</span></small>
			    <div class="clearfix"></div>
			   <a href="/" class="float-left mt-4"><img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" width="120"></a>
			  <div class="float-right">
				<a href="/login" class="btn text-secondary mt-3"><small>Back to login</small></a>
				 <button class="btn btn-secondary mt-3" id="btnSubmit">Submit</button>				
			  </div>
			   <div class="clearfix"></div>
		  </div>                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $('#btnSubmit').on('click',function() {
        sendChangePasswordEmail();
    });

    $('#inputEmail').keypress(function(e) {
        if(e.which == 13) {
            /* forgotPassword(); */
            sendChangePasswordEmail();
        }
    });

    var validateEmailAddress = function(email) {
        let isValid = false;

        $('.txt-alert').hide();

        if(!email) {
            $('.error-email')
                .text('Please enter your email address.').show();
        } else {
            let regex =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if(!regex.test(email)) {
                $('.error-email')
                    .text('Please enter a valid email address!').show();
            } else {
                isValid = true;
            }
        }

        return isValid;
    }

    var sendChangePasswordEmail = function() {
        let email = $('#inputEmail').val();

        if(validateEmailAddress(email)) {
            $.ajax({
                url: '/login/sendchangepasswordnotification',
                method: 'POST',
                data: { email:email },
                beforeSend: function() {
					$('#btnSubmit').html('<i class="fa fa-spin fa-spinner"></i> Checking...');
                },
                success: function(data) {
					console.log(data);

                    if(!data.status) {
                        $('.error-email')
                            .text('The email address you entered does not exists.').show();
                        return false;
                    }
					$('.note-info').show()
                        .find('.note-body')
                            .html('The change password link was sent to your email. Please check your email!');
                },
                complete: function() {
					$('#btnSubmit').html('Submit');
                },
            });
        }
    }
});
</script>