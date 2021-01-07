<?php //$this->load->view('project-owner/dashboard/header');?>
<?php //$this->load->view('project-owner/dashboard/navigation');?>

<?php if ($this->session->userdata('logged_in')):?>
    <?php if ($this->session->userdata('page')=='contractor'):?>
          <?php $this->load->view('dashboard/header');?>
          <?php $this->load->view('dashboard/navigation');?>
          <?php else:?>
          <?php $this->load->view('project-owner/dashboard/header');?>
          <?php $this->load->view('project-owner/dashboard/navigation');?>
    <?endif?>
   <?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Onboarding</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Onboarding</li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>
<style>
	body {
		background: #ffffff;
	}
	.page-footer {
		background: #fafafa;
	}
	.note-checklist{
		position: relative;
		border-bottom-right-radius: 60px 5px;
		border: 1px solid #E8E8E8;
		border-top-width: 1px;
		border-top-style: solid;
		border-top-color: rgb(232, 232, 232);
		border-top: 10px solid #fdfd86;
		background: #ffff88;
		background: -moz-linear-gradient(-45deg, #ffff88 81%, #ffff88 82%, #ffff88 82%, #ffffc6 100%);
		background: -webkit-gradient(linear, left top, right bottom, color-stop(81%,#ffff88), color-stop(82%,#ffff88), color-stop(82%,#ffff88), color-stop(100%,#ffffc6));
		background: -webkit-linear-gradient(-45deg, #ffff88 81%,#ffff88 82%,#ffff88 82%,#ffffc6 100%);
		background: -o-linear-gradient(-45deg, #ffff88 81%,#ffff88 82%,#ffff88 82%,#ffffc6 100%);
		background: -ms-linear-gradient(-45deg, #ffff88 81%,#ffff88 82%,#ffff88 82%,#ffffc6 100%);
		background: linear-gradient(135deg, #ffff88 81%,#ffff88 82%,#ffff88 82%,#ffffc6 100%);
		filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffff88', endColorstr='#ffffc6',GradientType=1 );
		display: block;
		padding: 2rem;
	}
	.text-light-black{
		color: #666;
		font-size: 1.5rem;
	}
	
	.wstrike {
		text-decoration: line-through !important;
	}
</style>
<div class="page-container">
	<div class="container">	
		<div class="row justify-content-center">
			<div class="col-lg-12 text-center py-5">
				<h3 class="text-secondary">
					<small>Welcome to ServiceChain, <?=ucwords(empty($firstname)?$username:$firstname.' '.$lastname)?></small>
				</h3>
				<h1>
					How would you like to get started?
				</h1>
			</div>
			<div class="col-lg-6">
				<img class="img-fluid" src="https://cdn.vnoc.com/background/bg-service-chain-1.png" alt="">
			</div>
			<div class="col-lg-6 pt-5">
				<div class="note-checklist">
					<ul class="list-unstyled">
						<?php
							foreach ($onboardingtasks as $row){
						?>
							
							<a href="<?=$row['url']?>" class="d-block text-light-black text-decoration-none mb-2 <?=$row['done']?'wstrike':''?> ">
								<div class="media">
									<?php
										if($row['done']){
											?>
											<i class="fa fa-check mt-2 mr-2"></i>
											<?php
										}else{
											?>
											<i class="far fa-circle mt-2 mr-2"></i>
											<?php
										}
									?>
									<div class="media-body mt-0">
										<?=$row['title']?>
									</div>
								</div>
							</a>
						<?php
							}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
	

<?php $this->load->view('project-owner/dashboard/footer');?>
