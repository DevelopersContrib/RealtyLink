<?php $this->load->view('project-owner/home/header');?>

<style>
body {
	background: #FAF6F2;
	position: relative;
	padding: 80px 0px 20px;
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
}
.s-form-inner {
	padding: 25px 5px 25px 20px;
}
.col-with-bg {
	background: linear-gradient(rgba(140, 98, 57, 0.85), rgba(83, 58, 34, 0.55)), rgba(140, 98, 57, 0.55) url('https://cdn.vnoc.com/mailchannel/businessman.jpg');
	background-color: #8C6239;
	background-size: cover;
	position: relative;
	border-radius: 0px 8px 8px 0px;
}
.col-with-bg .cwb {
	background: none;
	color: #ffffff;
	padding: 0px 25px 0px;
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
.signwith-box img {
    height: 25px;
    background: none;
    padding: 2px;
	border-radius: 4px;
}

.signwith-box .col1 {
    margin: 5px;
}

.signvia {
	margin: 10px 0 5px;
}

.success-container-alert {
        background: #ffffff;
        border: none;
       padding: 0px;
	box-shadow: 0 6px 15px rgba(0, 0, 0, 0.16);
}
.success-container-alert .fa-check-circle {
        font-size: 5rem;
}
.success-container-alert h3 {
        font-weight: 600;
        font-size: 20px;
}
.txt-alert {
        position: absolute;
        font-size: 12px;
        color: red;
        bottom: -11px;
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
.logo-on {
	display: none;
}
@media (max-width: 991.98px) { 
	.col-with-bg {
		display: none;		
	}
	.col-with-form {
		-ms-flex: 0 0 100%;
		flex: 0 0 100%;
		max-width: 100%;
	}
	.s-form-inner {
	    padding: 30px;
	}
	.logo-on {
		display: block;
	}
}

.tok-desc {   
	border-bottom: 1px dashed  #e9e9e9;
	font-size: 1.5rem;
	margin-top: 15px;
	padding-bottom: 5px;
}
	/* Coin Rotate and Shine ----------------------------- */
	.coin-rotate{
        position: relative;
        height: 90px;
        width: 90px;
        -webkit-transform-style: preserve-3d;
        transform-style: preserve-3d;
        -webkit-animation: rotate3d 4s linear infinite;
        animation: rotate3d 4s linear infinite;
        transition: all .3s;
    }
    .shine{
        position: relative;
        overflow: hidden;
        height: 90px;
        width: 90px;
        overflow: hidden;
        border-radius: 100%;
    }
    .shine:after{
        content: "";
        position: absolute;
        left: -150px;
        bottom: 100%;
        display: block;
        height: 200px;
        width: 600px;
        background: #fff;
        opacity: 0.3;
        -webkit-animation: shine linear 4s infinite;
        animation: shine linear 4s infinite;
    }
    @-webkit-keyframes rotate3d {
        0%, 50% {
            -webkit-transform: perspective(1000px) rotateY(0deg);
            transform: perspective(1000px) rotateY(0deg);
        }
        100% {
            -webkit-transform: perspective(1000px) rotateY(360deg);
            transform: perspective(1000px) rotateY(360deg);
        }
    }

    @keyframes rotate3d {
        0%, 50% {
            -webkit-transform: perspective(1000px) rotateY(0deg);
            transform: perspective(1000px) rotateY(0deg);
        }
        100% {
            -webkit-transform: perspective(1000px) rotateY(360deg);
            transform: perspective(1000px) rotateY(360deg);
        }
    }
    @-webkit-keyframes shine {
        0%, 15% {
            -webkit-transform: translateY(600px) rotate(-40deg);
            transform: translateY(600px) rotate(-40deg);
        }
        50% {
            -webkit-transform: translateY(-300px) rotate(-40deg);
            transform: translateY(-300px) rotate(-40deg);
        }
    }
    @keyframes shine {
        0%, 15% {
            -webkit-transform: translateY(600px) rotate(-40deg);
            transform: translateY(600px) rotate(-40deg);
        }
        50% {
            -webkit-transform: translateY(-300px) rotate(-40deg);
            transform: translateY(-300px) rotate(-40deg);
        }
    }
</style>
<form class="form-signin form-signup" action="javascript:validateForm();">	
	<div class="form-inner" style="display:block;">
		<div class="row">
			<div class="col-md-7 col-with-form">
				<div class="s-form-inner">
					<!-- -->	
					<div class="text-center logo-on">
						<a href="/"><img class="mb-2 img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" alt="" width="220" ></a>
					</div>
					<div class="form-label-group">
						<input type="text" id="owner_firstname" class="form-control" placeholder="First Name">
						<label for="inputEmail">First Name</label>
						<span class="txt-alert" id="error_owner_username" style="display: none"></span>
					</div>
					<div class="form-label-group">
						<input type="text" id="owner_lastname" class="form-control" placeholder="Last Name">
						<label for="inputEmail">Last Name</label>
						<span class="txt-alert" id="error_owner_username" style="display: none"></span>
					</div>
					<div class="form-label-group">
						<input type="text" id="owner_username" class="form-control" placeholder="Username">
						<label for="inputEmail">Username</label>
						<span class="txt-alert" id="error_owner_username" style="display: none"></span>
					</div>
					<div class="form-label-group">
						<input type="text" id="owner_email" class="form-control" placeholder="Email address">
						<label for="inputEmail">Email address</label>
						<span class="txt-alert" id="error_owner_email"  style="display:none;">This is an alert message!</span>
					</div>
					<div class="form-label-group">
						<input type="password" id="owner_password" class="form-control" placeholder="Password">
						<label for="inputPassword">Password</label>
						<span class="txt-alert" id="error_owner_password" style="display:none;">This is an alert message!</span>
					</div>
					<div class="form-label-group">
						<input type="password" id="owner_password2" class="form-control" placeholder="Confirm Password">
						<label for="inputConfirmPassword">Confirm Password</label>
						<span class="txt-alert" id="error_owner_password2" style="display:none;">This is an alert message!</span>
					</div>
					<div class="form-label-group flg mb-2">
						<div class="input-group mb-3">
							<select name="owner_country" id="owner_country" class="form-control custom-select">
								<option value=""> Phone Number </option>
								<?php if ($countries->num_rows() > 0):?>
									<?php foreach ($countries->result() as $row):?>
									<option value="<?php echo $row->country_id?>"><?php echo $row->name.' +'.$row->phone_code?></option>
									<?php endforeach;?>
								<?php endif?>
							</select>
							<div class="input-group-prepend">
								<input type="text" id="owner_phone" class="form-control" placeholder="phone number">
								<span class="txt-alert" style="display:none;">Invalid Country Code</span> 
							</div>
						</div> 
						
					</div>
					
					<div class="form-label-group mb-2">
						<div class="float-right"><small>Already a member? &nbsp;<a href="/project-owner/login">Please Log In</a></small></div>
						<div class="clearfix"></div>
					</div>
					<!-- <div class="g-recaptcha" data-sitekey="your_site_key"></div> -->
					<button class="btn btn-lg btn-secondary btn-block" id="btnCreateAccount" type="submit">Create Account</button>
					<div class="signwith-box text-center">
						<p class="signvia">Sign Up With</p>
						<div class="row justify-content-center">
							<div class="col1">
								<a href="<?=$login_contrib?>"><img class="img-fluid" src="https://www.referrals.com/assets/uploads/logos/logo-new-contrib-06.png" title="Contrib"></a>
							</div>
							<div class="col1">
								<a href="<?=$login_vnoc?>"><img class="img-fluid" src="https://www.referrals.com/assets/uploads/logos/logo-vnoc4.png" title="VNOC"></a>
							</div>
							<div class="col1">
								<a href="<?=$login_handyman?>"><img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-handyman.png" title="Handyman"></a>
							</div>			
						</div>
					</div>
					<p class="mt-3 mb-3 text-muted text-center">&copy; <?=date('Y')?> Servicechain.com</p>
					<!-- -->
				</div>
			</div>
			<div class="col-md-5 col-with-bg">
				<div class="row align-items-center h-100">
					<div class="col-md-12 mx-auto">
						<div class="cwb">								
							<div class="text-center mb-2">
								<h1 class="h2 mb-3">Project Owner Sign Up</h1>
							</div>							
							<div class="text-center mb-2">								
								<img class="img-fluid" src="https://cdn.vnoc.com/background/bg-service-chain-1.png">
							</div>
							<div class="text-center">
								<p>
								A Transparent Contribution Platform for Digital Assets on the Blockchain 
								</p>
							</div>
							<div class="text-center">
								<a href="/"><img class="mt2-2 img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" alt="" ></a>
							</div>
							<div class="text-center mt-5">
								<small>
								<a href="home/"><i class="fa fa-home" aria-hidden="true"></i></a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href="/signup">Join as service provider</a>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="success-container-alert alert alert-secondary d-none">
		<div class="row">
			<div class="col-md-6 col-with-form">
				<div class="s-form-inner">
					<div class="text-center"><h3><p><i class="far fa-check-circle"></i></p> Thank you for signing up!<br> The activation email is on the way, <br>it was sent to<br> 
						<span id="email_return"></span><br> If it takes too long to arrive, please click resend button below<p class="mt-3"> <a href="javascript:resendCode();" class="btn btn-secondary" id="btnResend">Resend</a></p></h3>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-with-bg" style="background: linear-gradient(rgba(60, 42, 25, 0.85), rgba(140, 98, 57, 0.85)), rgba(140, 98, 57, 0.85) url('http://cdn.vnoc.com/mailchannel/businessman.jpg');">
				<div class="row align-items-center h-100">
					<div class="col-md-12 mx-auto">
						<div class="cwb">	
							<div class="text-center mb-5">
								<a href="/"><img class="mt2-2 img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" alt="" width="220" ></a>
								<div class="coin-rotate my-5 mx-auto">
									<div class="shine">
										<img src="https://cdn.vnoc.com/icons/token-service-chain.png" class="d-inline-block" width="90">
									</div>
								</div>
							</div>
							<div class="mb-2">
								<h2 class="tok-desc">
									<span class="float-left">Token Left:</span>
									<span class="float-right"><?=number_format($this->cryptoapi->gettokenbalance())?></span>
									<div class="clearfix"></div>
								</h2>
								<div class="clearfix"></div>
								<h2 class="tok-desc">
									<span class="float-left">Token Sold:</span>
									<span class="float-right"><?=number_format($this->cryptoapi->gettokensold())?></span>
									<div class="clearfix"></div>
								</h2>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<?php $this->load->view('project-owner/home/footer');?>
 <script src="/assets/js/project-owner/signup/signup.js"></script>
 <script>
 $(document).ready(function(){
		$(document).keypress(function(e) {
			if(e.which == 13) {
				validateForm();
			}
		});
	});
 </script>


