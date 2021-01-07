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
			<li class="breadcrumb-item active" aria-current="page"><?php echo $project->row()->title?></li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="/projects">Projects</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $project->row()->title?></li>
		</ol>
	</div>
	<?php endif?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>

<link rel="stylesheet" href="/assets/css/project.css">
<style>
<?php if ($project->row()->cover_image != null):?>
.project-cover-container {
	background: url('<?php echo $project->row()->cover_image?>') !important;
	background-size: cover !important;
	background-position: center;
	background-repeat: no-repeat !important;
	position: relative;
}
<?php else:?>

.project-cover-container {
	background: #333 url('http://cdn.vnoc.com/mailchannel/businessman.jpg');
	background-size: cover !important;
	background-position: center;
	background-repeat: no-repeat !important;
	position: relative;
}
<?php endif?>
.page-container {
	padding: 0px 0px 100px;
}
.projtop {
    background: none;
    padding: 80px 0px 50px;
}
.badge-info {
	background: #C69C6D;
}
.fs-35 {
	font-size: 30px;
	color: #666;
}
.nl2 {
	padding: 1rem 1rem;
	font-size: 1rem !important;
}
</style>
<div class="page-container">
	<div class="project-cover-container">
		<div class="container mb-3">
			<div class="row">
				<div class="col-md-12">
					<div class="projtop mb-3">
						<div class="media">
						    <?php if ($project->row()->icon_image == null):?>
							<img src="https://cdn.vnoc.com/servicechain/project-default.png" class="projlist-image mr-3 border p-1 rounded" alt="" width="80" width="80">
							<?php else:?>
						    <img src="<?php echo $project->row()->icon_image?>" class="mr-3 border p-1 rounded" alt="" width="80" width="80">
							<?php endif?>
							<div class="media-body">
								<h5 class="mt-0"><?php echo $project->row()->title?></h5>
								<div class="asstats mt-1">
									<span class=" stat-new badge badge-info"><?php echo $project->row()->status?></span>
									<!--
									<span class="stats-not-active stat-in-progess badge badge-secondary">In Progess</span>
									<span class="stats-not-active stat-completed badge badge-success">Completed</span>
									-->
								</div>
							</div>
							<div class="ml-3 text-right">
							<?php if ($this->session->userdata('logged_in')):?>
							   <?php if ($this->session->userdata('userid')==$project->row()->userid):?>
								<div class="btn-group btn-group-sm drp-btn-group-project">
									<button type="button" class="btn btn-light border-bg text-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-edit" aria-hidden="true"></i>
									</button>
									<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 31px, 0px);">
										<button class="dropdown-item " type="button">
											<i class="fas fa-pencil-alt mr-2" aria-hidden="true"></i>
											Edit
										</button>
										<button class="dropdown-item" type="button">
											<i class="far fa-trash-alt mr-2" aria-hidden="true"></i>
											Delete
										</button>
									</div>
								</div>
								<?php endif?>
								<?php endif?>								
								<div class="esh-box mt-3">
								<span class="badge badge-secondary">
									ESH Balance: <?php echo $this->cryptoapi->geteshbalance($project->row()->id)?> 
								</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="projtop mb-3" style="display: none;">
					<div class="media">
					    <?php if ($project->row()->icon_image == null):?>
						<img src="https://cdn.vnoc.com/servicechain/project-default.png" class="mr-3 border p-1 rounded" alt="" width="64" width="64">
						<?php else:?>
					    <img src="<?php echo $project->row()->icon_image?>" class="mr-3 border p-1 rounded" alt="" width="64" width="64">
						<?php endif?>
						<div class="media-body">
							<h5 class="mt-0"><?php echo $project->row()->title?></h5>
						</div>
						<div class="ml-3">
						<?php if ($this->session->userdata('logged_in')):?>
    						   <?php if ($this->session->userdata('userid')==$project->row()->userid):?>
    							<div class="btn-group btn-group-sm drp-btn-group-project">
    								<button type="button" class="btn btn-light border text-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    									<i class="fas fa-ellipsis-h" aria-hidden="true"></i>
    								</button>
    								<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(32px, 31px, 0px);">
    									<button class="dropdown-item " type="button">
    										<i class="fas fa-pencil-alt mr-2" aria-hidden="true"></i>
    										Edit
    									</button>
    									<button class="dropdown-item" type="button">
    										<i class="far fa-trash-alt mr-2" aria-hidden="true"></i>
    										Delete
    									</button>
    								</div>
    							</div>
    							<?php endif?>
							<?php endif?>
						</div>
					</div>
				</div>
				
				<div class="details-tabber">
					<ul class="nav nav-pills mb-3 nav-justified" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link nl2 active" id="pills-details-tab" data-toggle="pill" href="#pills-details" role="tab" aria-controls="pills-details" aria-selected="true">
								<i class="fas fa-th-list"></i>&nbsp;Details
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link nl2" id="pills-tasks-tab" data-toggle="pill" href="#pills-tasks" role="tab" aria-controls="pills-tasks" aria-selected="false">
								<i class="fas fa-tasks"></i>&nbsp;Tasks
							</a>
						</li>
					</ul>
					<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-details" role="tabpanel" aria-labelledby="pills-details-tab">
							<div class="row">
								<!-- detail col -->
								<div class="col-lg-12 mb-2">
									<div class="card projbox-card">
										<div class="card-header border-bottom-0">
											<div class="media media-userlink-projbox">
												<i class="fas fa-warehouse fs-35 mr-2"></i>
												<div class="media-body">
													<h5 class="mt-2 mb-0 text-capitalize f-600 project-username">Description</h5>
													<ul class="list-inline mb-0 projbox-stats-user"> </ul>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="media">
												<?php echo stripcslashes($project->row()->description)?>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 mb-2">
									<div class="card projbox-card">
										<div class="card-header border-bottom-0">
											<div class="media media-userlink-projbox">
												<i class="fas fa-address-card fs-35 mr-2"></i>
												<div class="media-body">
													<h5 class="mt-2 mb-0 text-capitalize f-600 project-username">Address</h5>
													<ul class="list-inline mb-0 projbox-stats-user"> </ul>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="media">
												<?php echo $project->row()->address.' '.$project->row()->city.' '.$project->row()->state.' '.$this->countrydata->getinfobyid('name',$project->row()->country_id).' '.$project->row()->zipcode?>
											</div>
										</div>
									</div>
								</div>
								<!-- detail col -->
								<div class="col-lg-12 mb-2">
									<div class="card projbox-card">
										<div class="card-header border-bottom-0">
											<div class="media media-userlink-projbox">
												<i class="fas fa-phone fs-35 mr-2"></i>
												<div class="media-body">
													<h5 class="mt-2 mb-0 text-capitalize f-600 project-username">Contact Details</h5>
													<ul class="list-inline mb-0 projbox-stats-user"> </ul>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="media">
												+<?php echo $this->countrydata->getinfobyid('phone_code',$project->row()->country_id).$project->row()->phone_number?>
											</div>
										</div>
									</div>
								</div>
								<!-- detail col -->
								
								<!-- detail col -->
								<div class="col-lg-12 mb-2">
									<div class="card projbox-card">
										<div class="card-header border-bottom-0">
											<div class="media media-userlink-projbox">
												<i class="fas fa-map-marked-alt fs-35 mr-2"></i>
												<div class="media-body">
													<h5 class="mt-2 mb-0 text-capitalize f-600 project-username">Location</h5>
													<ul class="list-inline mb-0 projbox-stats-user"> </ul>
												</div>
											</div>
										</div>
										<div class="card-body">
											<div class="media" style="position: relative; overflow: hidden;">
												<div class="map-container">
													<div class="mapouter">
													<div class="gmap_canvas">
														<iframe width="690" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo $address?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
													</div>
													<style>.mapouter{position:relative;text-align:right;height:400px;width:690px;}.gmap_canvas {overflow:hidden;background:none!important;height:400px;width:690px;}</style>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- end detail col -->
							</div>
						</div>
						<div class="tab-pane fade" id="pills-tasks" role="tabpanel" aria-labelledby="pills-tasks-tab">
							<!-- -->
							<div class="projbottom">					
								<div class="tab-style-minimal mb-3">
									<ul class="nav nav-pills nav-justified nav-pills-cust" id="myTab" role="tablist">
										<li class="nav-item"> <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#latest-tasks" role="tab" aria-selected="true">Latest Tasks</a> </li>
										<li class="nav-item"> <a class="nav-link" id="updates-tab" data-toggle="tab" href="#inprogress" role="tab" aria-selected="false"><span class="badge badge-success"><?php echo $this->projecttasksdata->getcountbyattribute('project_id',$project->row()->id,'status','in progress')?></span>&nbsp;In Progress</a> </li>
										<li class="nav-item"> <a class="nav-link" id="questions-tab" data-toggle="tab" href="#forapproval" role="tab" aria-selected="false"><span class="badge badge-success"><?php echo $this->projecttasksdata->getcountbyattribute('project_id',$project->row()->id,'status','for approval')?></span>&nbsp;For Approval</a> </li>
										<li class="nav-item"> <a class="nav-link" id="done-tab" data-toggle="tab" href="#done" role="tab" aria-selected="false"><span class="badge badge-success"><?php echo $this->projecttasksdata->getcountbyattribute('project_id',$project->row()->id,'status','completed')?></span>&nbsp;Completed Tasks</a> </li>
										
										
									</ul>
								</div>
								<div class="tab-content mt-3" id="myTabContent">
									<div class="tab-pane fade active show" id="latest-tasks" role="tabpanel">
										
									</div>
									<div class="tab-pane fade" id="done" role="tabpanel">
										
									</div>
									<div class="tab-pane fade" id="todo" role="tabpanel">
										
									</div>
									<div class="tab-pane fade" id="inprogress" role="tabpanel">
										
									</div>
									<div class="tab-pane fade" id="forapproval" role="tabpanel">
										
									</div>
									<div class="tab-pane fade" id="request" role="tabpanel">
									
									</div>
								</div>					
							</div>
							<!-- -->
						</div>
					</div>
				</div>
				
			</div>
			<div class="col-md-4">
				<div class="card latest-proj-boxes">
					<div class="card-header clpb">
						<div class="pt-2">Latest Projects</div>
					</div>
					<div class="card-body">
					<?php if ($qprojects->num_rows() > 0):?>
            			<?php foreach ($qprojects->result() as $prow):?>
            			   <p class="conlist"> <i class="fa fa-star-o" aria-hidden="true"></i>&nbsp; 
            				<a href="/project/details/<?php echo $prow->id?>/<?php echo $prow->slug?>" target="_blank">
            					<?php echo $prow->title?>                                                
            				</a> 
            			</p>
            			<?php endforeach;?>
				<?php endif;?>
					</div>
				</div>
				
				<!-- latest contractors-->
				<div class="card latest-con-boxes mt-4">
					<div class="card-header clpb">
						<div class="pt-2">Latest Members</div>
					</div>
					<div class="card-body">
						 <?php if ($qpeople->num_rows() > 0):?>
                		   		<?php foreach ($qpeople->result() as $piprow):?>
                		   			<p class="conlist">
                		   			    <?php if ($piprow->profile_image==null ):?>
                		   			    <img src="http://cdn.vnoc.com/servicechain/user-default.png" class="lcb-image rounded-circle" height="30" width="30">
                		   			    	<?php else:?>
                		   			    <img src="<?php echo $piprow->profile_image?>" class="lcb-image rounded-circle" height="30" width="30">	
                		   			    <?php endif?> 
                        				
                        				<a href="/profile/<?php echo $piprow->username?>" target="_blank">
                        					&nbsp;<?php echo $piprow->firstname.' '.$piprow->lastname?>                                                
                        				</a> 
                			      </p>
                		   		<?php endforeach;?>
		   				<?php endif?>
					</div>
				</div>
				<!-- -->
			</div>
		</div>
	</div>
</div>

<script>
$( document ).ready(function() {
	loadtasbystatus('latest-tasks');
	loadtasbystatus('done');
	loadtasbystatus('inprogress');
	loadtasbystatus('forapproval');
});

var project_id = '<?php echo $project->row()->id?>';
function loadtasbystatus(status){
	$.ajax({
		method: "POST",
		url:  "/project/loadmytasks",
		data: {status:status,
				project_id:project_id  
			}
	})
	.success(function( data ) {
		$('#'+data.status).html(data.html);
	});
}
</script>

<?php $this->load->view('dashboard/footer');?>	