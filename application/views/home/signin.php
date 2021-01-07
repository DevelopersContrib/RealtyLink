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
}
.s-form-inner {
	padding: 85px 30px 85px 15px;
}
.col-with-bg {
	background: linear-gradient(rgba(140, 98, 57, 0.85), rgba(89, 63, 37, 0.55)), rgba(140, 98, 57, 0.55) url('https://cdn.vnoc.com/mailchannel/businessman.jpg');
	background-color: #8C6239;
	background-size: cover;
	background-position: center;
	position: relative;
	border-radius: 8px 0px 0px 8px;
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
        padding: 60px 40px;
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
</style>
<div class="form-signin form-signup">    

        <div class="form-inner">
		<div class="row">
			<div class="col-md-5 col-with-bg">
				<div class="row align-items-center h-100">
					<div class="col-md-12 mx-auto">
						<div class="cwb">							
							<div class="text-center mb-2 mt-3">
								<h1 class="h2 mb-3">Service Provider Login</h1>
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
								<a href="/"><img class="mt2-2 img-fluid" src="https://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" alt="" ></a>
							</div>
							<div class="text-center mt-5">
								<small>
								<a href="/"><i class="fa fa-home" aria-hidden="true"></i></a>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href="project-owner/login">Signin as project owner</a>
								</small>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-7 col-with-form">				
				<div class="s-form-inner">
					<!-- -->	
					 <div class="text-center logo-on">
							<a href="/"><img class="mb-2 img-fluid" src="https://cdn.vnoc.com/logos/logo-ServiceChain3-b.png" alt="" width="220" ></a>
						</div>
					  <div class="form-label-group">
						   <input type="text" id="inputEmail" class="form-control" placeholder="Email address">
						   <label for="inputEmail">Email</label>
						   <span class="txt-alert d-none" id="email_error"></span>
					  </div>
					  <div class="form-label-group mb-2">
						   <input type="password" id="inputPassword" class="form-control" placeholder="Password">
						   <label for="inputPassword">Password</label>
						   <span class="txt-alert  d-none" id="password_error"></span>
					  </div>
					  <div class="form-label-group mb-2">
						    <div class="float-left"><small><a href="/forgot-password">Forgot Password?</a></small></div>
						   <div class="float-right"><small>Not a member yet? &nbsp;<a href="/signup">Create an account</a></small></div>
						   <div class="clearfix"></div>
					  </div>
					  <button class="btn btn-lg btn-secondary btn-block" id="login" type="button">Log In Now</button>
							<div class="signwith-box text-center">
							<p class="signvia">Log In Via</p>
							<div class="row justify-content-center">
								<div class="col1">
									<a href="<?=$login_contrib?>"><img class="img-fluid" src="https://www.referrals.com/assets/uploads/logos/logo-new-contrib-06.png" title="Contrib"></a>
								</div>
								<div class="col1">
									<a href="<?=$login_vnoc?>"><img class="img-fluid" src="https://www.referrals.com/assets/uploads/logos/logo-vnoc4.png" title="VNOC"></a>
								</div>
								<div class="col1">
									<a href="<?=$login_handyman?>"><img class="img-fluid" src="https://cdn.vnoc.com/logos/logo-handyman.png" title="Handyman"></a>
								</div>					
							</div>
						</div>
					  <p class="mt-3 mb-3 text-muted text-center">&copy;2019 Servicechain.com</p>
					<!-- -->
				</div>
			</div>
		</div>
		   
        </div>
</div>

<?php $this->load->view('home/footer');?>
<script src="/assets/js/signin/signin.js"></script>
<script>
$(document).ready(function(){
	  $(document).keypress(function(e) {
	    if(e.which == 13) {
	      $('#login').trigger('click');
	    }
	  });
	});
</script>


