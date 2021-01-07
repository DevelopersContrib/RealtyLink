<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<style>
	.brdr{
		border: 1px solid;
	}
	.bg-white-widget{
		background-color: #f1f1f1;
		display: flex;
		border-radius: 4px;
		height: 100%;
	}
	.bg-white-widget,
	.plans-payment-header,
	.plans-payment-body,
	.plans-payment-footer{
		flex-direction: column;
	}
	.plans-payment-header{
		display: flex;
	}	
	.plans-payment-body,
	.plans-payment-footer{
		display: flex;
		height: 100%;
	}
	.ul-plans-list li{
		border-top: 1px dashed #ddd;
		color: #74788d;
	}
	.ul-plans-list li i{
		color: #595d6e;
	}
	.ul-plans-list li i.fa-times,
	.text-plan-red{
		color: rgba(217,83,79,.8);
	}
	.plan-meta-title{
		color: #a2a5b9;
		font-weight: 700;
		font-size: 17px;
	}
	.plan-price{
		color: #595d6e;
		font-size: 3rem;
		line-height: normal;
	}
	.plan-price-num{
		font-weight: 800;
		display: inline-block;
	}
	.plan-price small {
		font-size: 60%;
	}
	.plans-payment-footer {
		flex-direction: column;
		justify-content: end;
	}
	.ul-plans-list .media-body{
		font-size: 13px;
	}

	.lg-title-plan-name{
		color: #595d6e;
		font-weight: 600;
		font-size: 3rem;
	}
	.bg-white-tabcontent{
		background-color: #fff;
	}
</style>
   	<?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Upgrade</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Upgrade</li>
		</ol>
	</div>
	<?php endif;?>

<div class="container py-5">
	<!--<ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#serviceprovider" role="tab" aria-selected="true">
				Service Provider Plan
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#projectowner" role="tab" aria-selected="false">
				Project Owner Plan
			</a>
		</li>
	</ul>-->
	<!--<div class="tab-content py-5 bg-white-tabcontent">-->
		<?php
			if($user_type == 'contractor'){
		?>
		<div class="tab-pane fade show active" id="serviceprovider" role="tabpanel">
			<!-- Start:: Service Provider Plan -->
			<div class="row">
				<div class="col-lg-12 mb-3">
					<h1 class="text-center lg-title-plan-name">
						Service Provider Plan
					</h1>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-lg-12 mb-12">
				<?php
					if($upgrade_plan_service_provider && isset($_GET['m']) && $_GET['m']=='success'){
				?>
				<h1 class="text-center">
					Payment Confirmation
				</h1>
				<div class="wrap-features-subscription">
					<p style="text-align:center">Your Account is successfully Subscribe to Premium Account!</p>
				</div>
				<?php
					}
				?>
				</div>
			</div>
			<div class="row justify-content-center">
				<?php
					if(!$upgrade_plan_service_provider){
				?>
					<div class="col-lg-4 mb-3">
						<div class="bg-white-widget">
							<div class="plans-payment-header text-center p-3">
								<div class="d-block plan-meta-title text-uppercase">free</div>
								<div class="d-block plan-price">
									<span class="plan-price-num mr-1">0.00</span><small>USD</small>
								</div>
							</div>
							<div class="plans-payment-body p-3">
								<ul class="list-unstyled mb-0 ul-plans-list">
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												FULL FEATURES
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Main blockchain node - <b>(Test)</b>
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Join a project
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Earn project tokens
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Service provider licenses
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Referral program
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Wallet - <b>(Test chain)</b>
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-times mr-3 mt-1"></i>
											<div class="media-body text-plan-red">
												Get eShares on Servicechain
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-times mr-3 mt-1"></i>
											<div class="media-body text-plan-red">
												Earn USDC
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-times mr-3 mt-1"></i>
											<div class="media-body text-plan-red">
												Featured  and verifited profile
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="plans-payment-footer text-center pr-3 pl-3 pb-3">
								<?php
									if($upgrade_plan_service_provider){
								?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled="">
									Free
								</a>
								<?php
									}else{
								?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled="">
									Current Plan
								</a>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				<?php
					}
				?>
				<div class="col-lg-4 mb-3">
					
					<div class="bg-white-widget">
						<div class="plans-payment-header text-center p-3">
							<div class="d-block plan-meta-title text-uppercase">Premium</div>
							<?php
								if(!$upgrade_plan){
							?>
							<div class="d-block plan-price">
								<span class="plan-price-num mr-1"><?=number_format($this->config->item('plan_amount_service_provider'),2)?></span><small>USD</small>
							</div>
							<?php }?>
						</div>
						<div class="plans-payment-body p-3">
							<ul class="list-unstyled mb-0 ul-plans-list">
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											FULL FEATURES
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Main blockchain node - <b>(Main node)</b>
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Join a project
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Earn project tokens
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Earn USDC
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Service provider licenses
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Referral program
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Wallet
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Get eShares on Servicechain
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-times mr-3 mt-1"></i>
										<div class="media-body text-plan-red">
											Featured  and verifited profile
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="plans-payment-footer text-center pr-3 pl-3 pb-3">
							<?php
								if(!$upgrade_plan_service_provider){
							?>
								<a href="/upgrade/purchase" class="btn btn-block btn-primary text-uppercase py-3">
									Upgrade
								</a>
							<?php
								}else{
							?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled>
									Current Plan
								</a>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<!-- End -->
		</div>
		<?php
			}else{
		?>
		<div class="tab-pane fade show active" id="projectowner" role="tabpanel">
			<!-- Start:: Project Owner Plan -->
			<div class="row">
				<div class="col-lg-12 mb-3">
					<h1 class="text-center lg-title-plan-name">
						Project Owner Plan
					</h1>
				</div>
			</div>
			<div class="row justify-content-center">
				<?php
					if(!$upgrade_plan){
				?>
					<div class="col-lg-4 mb-3">
						<div class="bg-white-widget">
							<div class="plans-payment-header text-center p-3">
								<div class="d-block plan-meta-title text-uppercase">free</div>
								<div class="d-block plan-price">
									<span class="plan-price-num mr-1">0.00</span><small>USD</small>
								</div>
							</div>
							<div class="plans-payment-body p-3">
								<ul class="list-unstyled mb-0 ul-plans-list">
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												FULL FEATURES
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Main blockchain node - <b>(Test)</b>
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Add a project
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Create project tokens - <b>(on test chain)</b>
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Create project smart contracts - <b>(on test chain)</b>
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-check mr-3 mt-1"></i>
											<div class="media-body">
												Referral program
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-times mr-3 mt-1"></i>
											<div class="media-body text-plan-red">
												Feature project on Servicechain and partner sites
											</div>
										</div>
									</li>
									<li class="py-3 px-3">
										<div class="media">
											<i class="fas fa-times mr-3 mt-1"></i>
											<div class="media-body text-plan-red">
												Get eShares on Servicechain
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="plans-payment-footer text-center pr-3 pl-3 pb-3">
								<?php
									if($upgrade_plan){
								?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled="">
									Free
								</a>
								<?php
									}else{
								?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled="">
									Current Plan
								</a>
								<?php
									}
								?>
							</div>
						</div>
					</div>
				<?php
					}
				?>
				<div class="col-lg-4 mb-3">
					
					<div class="bg-white-widget">
						<div class="plans-payment-header text-center p-3">
							<div class="d-block plan-meta-title text-uppercase">Premium</div>
							<?php
								if(!$upgrade_plan){
							?>
							<div class="d-block plan-price">
								<span class="plan-price-num mr-1"><?=number_format($this->config->item('plan_amount'),2)?></span><small>USD</small>
							</div>
							<?php }?>
						</div>
						<div class="plans-payment-body p-3">
							<ul class="list-unstyled mb-0 ul-plans-list">
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											FULL FEATURES
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Main blockchain node - <b>(Main)</b>
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Add a project
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Create project tokens - <b>(Yes on Main chain)</b>
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Create project smart contracts
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Feature project on Servicechain and partner sites
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-check mr-3 mt-1"></i>
										<div class="media-body">
											Referral program
										</div>
									</div>
								</li>
								<li class="py-3 px-3">
									<div class="media">
										<i class="fas fa-times mr-3 mt-1"></i>
										<div class="media-body text-plan-red">
											Get eShares on Servicechain
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="plans-payment-footer text-center pr-3 pl-3 pb-3">
							<?php
								if(!$upgrade_plan){
							?>
								<a href="/upgrade/purchase" class="btn btn-block btn-primary text-uppercase py-3">
									Upgrade
								</a>
							<?php
								}else{
							?>
								<a href="javascript:;" class="btn btn-block btn-secondary text-uppercase py-3 disabled" disabled>
									Current Plan
								</a>
							<?php
								}
							?>
						</div>
					</div>
				</div>
			</div>
			<!-- End -->
		</div>
		<?php 
			}
		?>
	<!--</div>-->
</div>

<?php $this->load->view('project-owner/dashboard/footer');?>
