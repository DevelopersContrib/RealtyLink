<?php if ($this->session->userdata('logged_in')):?>
<?php if ($this->session->userdata('page')=='contractor'):?>
    <?php $this->load->view('dashboard/header');?>
    <?php $this->load->view('dashboard/navigation');?>
    <?php else:?>
    <?php $this->load->view('project-owner/dashboard/header');?>
    <?php $this->load->view('project-owner/dashboard/navigation');?>
 <?php endif?>   

<?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $profile['firstname'].' '.$profile['lastname'];?> Profile</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $profile['firstname'].' '.$profile['lastname'];?> Profile</li>
		</ol>
	</div>
	<?php endif?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
<?php endif?>
<style>
.page-container {
    padding: 0px 0px 100px;
}
.user-profile-photo-inner {
	height: 144px;
	width: 144px;
	border-radius: 50%;
	box-shadow: 0 0 0 10px #fafafa;
	margin: auto;
}
.user-profile-inner .card-title {
	font-weight: 600;
}
.user-profile-inner .proj-title {
	font-weight: 600;
	margin-bottom: 10px;
}
.user-profile-inner .card {
	border: 1px solid #eaeaea;
}
.profile-details h5 {
	font-size: 2.2rem;
	font-weight: 800;
}
.aprolink {
	margin-bottom: 5px;
	background-color: #fafafa;
	border: none;
	padding-bottom: 17px;
}
.aprolink img {
	width: 100%;
}
.aprolink:hover {
	text-decoration: none;
}
.prodate {
	color: #666;
}
.list-group-item {
	border-left: none;
	border-right: none;
	padding: .75rem 1rem;
}
.list-group-item.active {
    color: #666;
    background-color: #dedede;
    border-color: none;
    border: none;
}
.active h5 {
	font-weight: 800;
	font-size: 1.2rem;
	color: #666;
}
.project-container {
    background: #fff;
    padding: 15px;
    border-radius: 0px 0px 8px 8px;
}
.project-container .badge {
    width: auto;
    padding: .3rem .4rem;
    font-size: 80%;
}
.project-container:hover {
    box-shadow: none;
}
.toks .alert {
	border-radius: 0px;
	margin-bottom: .2rem;
}
.tok-details {
	font-weight: 600;
}
.tok-value {
	font-weight: 800;
	font-size: 1.3rem;
	color: #eaeaea;
} 
.badge-info {
    color: #fff;
    background-color: #88735B;
}
.med-bdr {
	padding-bottom: 10px;
	margin-bottom: 10px;
}
.med-bdr .fas {
	color: #C69C6D;
}
.med-bdr .fa-gray {
	color: gray;
}
.med-bdr .fa-soc {
	font-size: 1rem;
}
.med-bdr p, .med-bdr .ul-overview-info  {
	font-size: .95rem;
}
.med-bdr h5 {
	font-size: 1.15rem;
	color: gray;
}
.profile-cover {		
	padding: 30px;
}
.profile-details {
	color: #ffffff;
}
.list-group-flush {
	width: 185px;
}
.card2 {
    background-color:  #fff;
    border: 1px solid #eaeaea;
    border-radius: 0px 0px 8px 8px;
    box-shadow: none !important;
}
.toks .alert {
    position: relative;
    padding: .35rem 1rem .15rem;
    border-radius: 8px;
}
.card-body {    
    padding: 1rem 1rem;
}
.alert-secondary {
	color: #fff;
	background-color: #8C6239;
	border-color: #C69C6D;
}
.badge-custom {
	padding: .65em .85em;
	font-size: 85%;
}
a.badge-custom:hover {
	background: #8C6239;
	opacity: .8;
}
.fnt-500 {
	font-size: 85%;
	font-weight: 600;
	color: #888;
}
.social-username {
	font-size: 84%;
	font-weight: 300;
}
.med-bdr a:hover {
	text-decoration: none;
}
.md-in {
	border-bottom: 1px dashed #eaeaea;
}
</style>
<div class="page-container">
	<div class="profile-cover" style="background: linear-gradient(rgba(202, 146, 83, 0.55), rgba(202, 146, 83, 0.55)), rgba(202, 146, 83, 0.55) url('https://cdn.vnoc.com/background/business-note-bg.jpg');background-size: cover;background-position: bottom;">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="profile-cover-inner">
						<div class="media">
						 <?php
							$profile_image = !empty($profile['profile_image'])?$profile['profile_image']:'http://cdn.vnoc.com/servicechain/user-default.png';
							?>
							<img class="user-profile-photo-inner mr-3 align-self-center" src="<?=$profile_image?>">
							<div class="media-body mt-5">
							    <div class="profile-details">
								<?php
									if(!empty($profile['firstname'])){
										$name = $profile['firstname'].' '.$profile['lastname'];
									}else{
										$name = $profile['username'];
									}
								?>
								<h5><?=$name?></h5>
								<p class="mt-1">
									<small>
									Joined
									<i aria-hidden="true" class="far fa-calendar-alt"></i>
									<?=date('M j, Y', strtotime($profile['signup_date']))?>
									</small>
								</p>
								<p class="mt-1">
									<a href="" class="badge badge-info badge-custom">Edit</a>
								</p>
								</div>
							</div>
							<div class="ml-3">
								<div class="list-group list-group-flush toks">
									<div class="alert alert-secondary" role="alert">						
										<div class="float-left tok-details">
											USDC
										</div>
										<div class="float-right tok-value">
											<?php echo $this->membersdata->getusdctotal($profile['id'])?>
										</div>
									</div>
									<div class="alert alert-secondary" role="alert">						
										<div class="float-left tok-details">
											<?php echo $this->config->item('servicechain_token')?>
										</div>
										<div class="float-right tok-value">
											<?php echo $this->membersdata->geteshtotal($profile['id'])?>
										</div>
									</div>
								</div>	
							</div>
						</div>				
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container user-profile-inner">
		<div class="row">
			<div class="col-md-4">				
				<div class="card2 text-center pt-2 pb-0">					
					<div class="card-body">						
						<div class="media med-bdr">							
							<i class="fas fa-chevron-circle-right  pt-1 mr-1"></i>
							<div class="media-body">
								<h5 class="mt-0">License Information</h5>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">License Number</span>
									</div>
									<span class="fnt-500 mr-2"><?if(!empty($licenseDetails->row()->license_no)) echo $licenseDetails->row()->license_no?></span>
								</div>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Status</span>
									</div>
									<span class="fnt-500 mr-2"><?if(!empty($licenseDetails->row()->status)) echo $licenseDetails->row()->status?></span>
								</div>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-4">
												<span class="small text-uppercase text-light-gray">Type</span>
											</div>
											<div class="col-md-8 text-right pr-4">
												<span class="fnt-500"><?if(!empty($licenseDetails->row()->type)) echo $licenseDetails->row()->type?></span>
											</div>
										</div>										
									</div>									
								</div>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Date Issued</span>
									</div>
									<?php 
										$dateIssued = '';
										if(!empty($licenseDetails->row()->date_issued)) {
											$dateIssued = strtotime($licenseDetails->row()->date_issued);
											$dateIssued = date('M j, Y',$dateIssued);
										}
									?>
									<span class="fnt-500 mr-3"><?=$dateIssued?></span>
								</div>							
							</div>
						</div>
						<div class="media med-bdr">
							<i class="fas fa-chevron-circle-right  pt-1 mr-1"></i>
							<div class="media-body">
							<h5 class="mt-0">Bond Information</h5>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Bonded Agent</span>
									</div>
									<span class="fnt-500 mr-2"><?if(!empty($bondDetails->row()->bond_agent)) echo $bondDetails->row()->bond_agent?></span>
								</div>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Bond Value</span>
									</div>
									<span class="fnt-500 mr-2"><?if(!empty($bondDetails->row()->bond_value)) echo $bondDetails->row()->bond_value?></span>
								</div>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Bond Info</span>
									</div>
									<span class="fnt-500 mr-2"><?if(!empty($bondDetails->row()->info)) echo $bondDetails->row()->info?></span>
								</div>							
							</div>
						</div>
						<div class="media med-bdr">
							<i class="fas fa-chevron-circle-right  pt-1 mr-1"></i>
							<div class="media-body">
							<h5 class="mt-0">Contact Details</h5>
								<div class="media md-in">
									<div class="media-body">
										<i class="fa fa-map-marker fa-gray"></i>&nbsp;<span class="small text-uppercase text-light-gray"><?=$this->countrydata->getinfobyid('name',$memberDetails->row()->country_id)?></span>
									</div>
								</div>
								<?php if(!empty($memberDetails->row()->phone_number)): ?>
								<div class="media">
									<div class="media-body">
										<i class="fa fa-phone fa-gray"></i>&nbsp;<span class="small text-uppercase text-light-gray"><?=$memberDetails->row()->phone_number?></span>
									</div>
								</div>
								<?php endif; ?>
								<div class="media md-in d-none">
									<div class="media-body">
										<i class="fa fa-envelope fa-gray"></i>&nbsp;<span class="small text-uppercase text-light-gray"><?if($memberDetails->row()->email) echo $memberDetails->row()->email?></span>
									</div>
								</div>							
							</div>
						</div>
						<?php if($socials->num_rows() > 0): ?>
						<div class="media med-bdr">
							<i class="fas fa-chevron-circle-right pt-1 mr-1"></i>							
							<div class="media-body">
								<h5 class="mt-0">Social Network</h5>
								<?php if(!empty($socials->row()->facebook_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-facebook fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->facebook_url?>">
													<span class="social-username"><?=$socials->row()->facebook_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
								<?php if(!empty($socials->row()->twitter_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-twitter fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->twitter_url?>">
													<span class="social-username"><?=$socials->row()->twitter_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
								<?php if(!empty($socials->row()->instagram_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-instagram fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->instagram_url?>">
													<span class="social-username"><?=$socials->row()->instagram_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
								<?php if(!empty($socials->row()->github_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-github fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->github_url?>">
													<span class="social-username"><?=$socials->row()->github_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
								<?php if(!empty($socials->row()->skype_username)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-skype fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->skype_username?>">
													<span class="social-username"><?=$socials->row()->skype_username?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
								<?php if(!empty($socials->row()->telegram)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-telegram-plane fa-gray fa-soc"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$socials->row()->telegram?>">
													<span class="social-username"><?=$socials->row()->telegram?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>
							</div>
						</div>
						<?php endif; ?>
						<div class="media med-bdr d-none">
							<i class="fas fa-chevron-circle-right  pt-1 mr-1"></i>
							<div class="media-body">
							<h5 class="mt-0">Business Details</h5>
								<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray">Specializes home decoration</span>
									</div>
								</div>
							</div>
						</div>
						<div class="media med-bdr">
							<i class="fas fa-chevron-circle-right  pt-1 mr-1"></i>
							<div class="media-body">
							<h5 class="mt-0">Payment Details</h5>
							<div class="media md-in">
									<div class="media-body">
										<span class="small text-uppercase text-light-gray"><?=$memberPlan->num_rows() > 0 ? 'Plan':'Free'?></span>
									</div>
									<?php if($memberPlan->num_rows()): ?>
									<span class="fnt-500 mr-2"><?=$memberPlan->row()->amount?></span>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>							
				</div>
			</div>
			<div class="col-md-8 pl-0">
				<nav class="page-next pagination-container"></nav>
				<ul class="list-group project-container">
					<div class="list-group-item active"><h5 class="mb-0"><i class="fas fa-tasks"></i>&nbsp;Tasks</h5></div>
					<div class="task-loader-loading" style="display: none">
						<div class="text-center">
                              <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                              </div>
                            </div>
					</div>			
					<div  id="taskcontainer" style="width:100%;">
					</div>
						
					
				</ul>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('dashboard/footer');?>
<script>
function getTasksLatest(page = '1'){
   		$('.task-loader-loading').show();
			$('#taskcontainer').html(' ');

			$.ajax({
				method: "POST",
				url:  "/taskajax/loadmytasks/<?php echo $profile['id']?>",
				data: { 'page':page}
			})
			.success(function( data ) {
				$('.task-loader-loading').hide();
				$('#taskcontainer').html(data);
				
			});
		}
$(document).ready( function () {
	getTasksLatest(1);
});
</script>
