<?php  if ($this->session->userdata('page')=='homeowner'):?>
    <?php $this->load->view('project-owner/dashboard/header');?>
    <?php $this->load->view('project-owner/dashboard/navigation');?>
<?php else:?>
	 <?php $this->load->view('dashboard/header');?>
    <?php $this->load->view('dashboard/navigation');?>
<?php endif?>
<div class="breadcrumb-outer">
	<ol class="container breadcrumb">
		<?php
			if ($this->session->userdata('logged_in')){
		?>
		<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
		<?php
			}else{
		?>
		<li class="breadcrumb-item"><a href="/project-owner/">Home</a></li>
		<?php
			}
		?>
		<li class="breadcrumb-item active"><?=$username?></li>
	</ol>
</div>
<style>
.user-profile-photo-inner {
	height: 116px;
	width: 116px;
	border-radius: 50%;
	border: 1px solid #dedede;
	margin: auto;
}
.user-profile-inner .card-title {
	font-weight: 600;
}
.user-profile-inner .proj-title {
	font-weight: 600;
	margin-bottom: 0px;
}
.user-profile-inner .card {
	border: 1px solid #eaeaea;
}
.aprolink {
	margin-bottom: 0px;
	background-color: #fafafa;
	border: none;
	padding-bottom: 17px;
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
}
.list-group-item.active {
    color: #333;
    background-color: #F9B971;
    border-color: none;
    border: none;
}
.active h5 {
	font-weight: 800;
	font-size: 1.2rem;
	color: #ffffff;
}
.project-container {
    background: #fff;
    padding: 15px;
}
.project-container .badge {
    width: auto;
}
.project-container:hover {
    box-shadow: none;
}
.card-body {
    padding: 1rem 1rem;
}
.card2 {
	background-color: #fff;
	border: 1px solid  #eaeaea;
	border-radius: 8px 8px 8px 8px;
	box-shadow: none !important;
}
.med-bdr .fas {
    color: #C69C6D;
}
.md-in {
    border-bottom: 1px dashed #eaeaea;
}
.media {
    align-items: flex-start;
    display: flex;
    text-align: left;
}
.med-bdr h5 {
    font-size: 1.15rem;
    color: 
    gray;
}
.social-username {
    font-size: 84%;
    font-weight: 300;
}
.med-bdr .fa-soc {
    font-size: 1rem;
}
.med-bdr .fa-gray {
    color: 
    gray;
}
</style>
<div class="page-container">
	<div class="container user-profile-inner">
		<div class="row">			
			<div class="col-md-4">
				<div class="card text-center pt-4 pb-4">
					<?php
						$profile_image = !empty($profile['profile_image'])?$profile['profile_image']:'http://cdn.vnoc.com/servicechain/user-default.png';
					?>
					<img class="user-profile-photo-inner" src="<?=$profile_image?>">
					<div class="card-body">						
						<?php
							if(!empty($profile['firstname'])){
								$name = $profile['firstname'].' '.$profile['lastname'];
							}else{
								$name = $profile['username'];
							}
						?>
						<h5 class="card-title"><?=$name?></h5>
						<p class="card-text">
							Joined
							<i aria-hidden="true" class="far fa-calendar-alt"></i>
							<?=date('M j, Y', strtotime($profile['signup_date']))?>
						</p>
					</div>
					<ul class="list-group list-group-flush d-none">
						<li class="list-group-item">...</li>
					</ul>
					<div class="card-body d-none">
						<a href="#" class="card-link">...</a>
					</div>
				</div>
				<?php if($user_socials->num_rows() > 0): ?>	
				<div class="card2 mt-2">
					<div class="card-body">
						<div class="media med-bdr">
							<i class="fas fa-chevron-circle-right pt-1 mr-1" aria-hidden="true"></i>							
							<div class="media-body">
								<h5 class="mt-0">Social Network</h5>
								<?php if(!empty($user_socials->row()->facebook_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-facebook fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$user_socials->row()->facebook_url?>">
													<span class="social-username"><?=$user_socials->row()->facebook_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
								<?php if(!empty($user_socials->row()->twitter_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-twitter fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$user_socials->row()->twitter_url?>">
													<span class="social-username"><?=$user_socials->row()->twitter_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
								<?php if(!empty($user_socials->row()->instagram_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-instagram fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$user_socials->row()->instagram_url?>">
													<span class="social-username"><?=$user_socials->row()->instagram_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
								<?php if(!empty($user_socials->row()->github_url)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-github fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="<?=$user_socials->row()->github_url?>">
													<span class="social-username"><?=$user_socials->row()->github_url?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
								<?php if(!empty($user_socials->row()->skype_username)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-skype fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="javascript:;">
													<span class="social-username"><?=$user_socials->row()->skype_username?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
								<?php if(!empty($user_socials->row()->telegram)): ?>
								<div class="media md-in">
									<div class="media-body">
										<div class="row">
											<div class="col-md-2">
												<span class="small text-uppercase text-light-gray">
													<i class="fab fa-telegram-plane fa-gray fa-soc" aria-hidden="true"></i>
												</span>
											</div>
											<div class="col-md-10 text-right pr-4">
												<a href="javascript:;">
													<span class="social-username"><?=$user_socials->row()->telegram?></span>
												</a>
											</div>
										</div>										
									</div>									
								</div>
								<?php endif; ?>								
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-md-8">
				<nav class="page-next pagination-container"></nav>
				<ul class="list-group project-container">
					<div class="list-group-item active mb-1"><h5 class="mb-0"><i class="fas fa-tasks"></i>&nbsp;Projects</h5></div>
					
				</ul>
			</div>
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
	loadpaginate();
	function loadpaginate(pages) {
		$.ajax({
			url: '/project-owner/profile/loadprojects',
			type: 'POST',
			dataType: 'json',
			data: { pages: pages, search_key:$('#search_key').val(),user:"<?=$profile['username']?>"},
			beforeSend: function () {
				loader();
			},
			success: function (data) {
				$('.project-container .project').remove();
				$('.project-container').append(data.html);
				$('.pagination-container').html(data.pagination);
				$('.loader').hide();
				
			},
			error: function () {
				alert('Something went Wrong. Please Reload The Page , Sorry for Inconvience');
			}
		});
	}

	function loader(){
		$('.loader').show();
	}
});
</script>
<?php $this->load->view('project-owner/dashboard/footer');?>