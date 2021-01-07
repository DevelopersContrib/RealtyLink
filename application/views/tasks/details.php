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
			<li class="breadcrumb-item"><a href="/project/details/<?php echo $project->row()->id?>/<?php echo $project->row()->slug?>"><?php echo $project->row()->title?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $task->row()->title?></li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="/project-owner/project/<?php echo $project->row()->id?>/<?php echo $project->row()->slug?>"><?php echo $project->row()->title?></a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $task->row()->title?></li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>

<?php endif?>
<link rel="stylesheet" href="/assets/css/star-static.css">
<script>
function applytask(id){
	$('#applyTaskModal').modal('show');
}

function processApplication(){
	$('#appError').hide();
	$('#appError').html('');
    var task_id = $('#task_details_id').val();
    var message = $('#apply_message').val();

    if (message == ""){
    	$('#appError').html('Please enter message');
    	$('#apply_message').focus();
    	$('#appError').show();
    }else {
        $('#btnSubmitApp').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Processing').attr('disabled',true);

        $.post('/tasks/saveapplication',{
            task_id:task_id,
            message:message
        },function(data){
            if(data.status){
                window.location = '/tasks/details/'+data.task_id+'/'+data.slug;
            }else{
            	$('#appError').html(data.message);
            	$('#appError').show();
            }
        });
    }
	
}
</script>
<style>
body {
	background: #f2f2f2;
}
.card-header {
    background-color: #fafafa;
}
.user-profile-photo {
    height: 24px;
    width: 24px;
    border-radius: 50%;
}
.details-inner h6 {
	font-weight: 600;
}
.details-inner .row-description img {
	width: 100%;
}
.card-header {
    font-weight: 600;
    font-size: 1.2rem;
}
.badge {
    padding: 1em 1em;
}
.btn-apply {
	font-weight: 600;
}
.page-footer {
    background: #ffffff;
}

.row-image {
	margin-top: 1rem;
	margin-bottom: 1rem;
	padding-bottom: 20px;
	border-bottom: 1px solid rgba(0,0,0,.1);
}
.task-rating-desc {
	font-weight: 600;
	font-size: 1rem;
}
</style>
<div class="page-container p-home">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="details-inner">
					<div class="card mb-2">
						<div class="card-header">
							<div class="row">
								<div class="col-md-7">
									<?php echo $task->row()->title?>
								</div>
								<div class="col-md-5 text-right">
									<?php if($task->row()->status == 'completed'): ?>
										<div class="task-rating-desc" >
											Project Owner Rating:&nbsp;
											<?php $rating_po = $this->memberrating->getbyattribute('member_id',$user->row()->id,'task_id',$task->row()->id); ?>
											<?php 
												if($rating_po->num_rows() == 0){
													
													echo "<b>No ratings yet</b>";
												}else{
													$rating_po = $rating_po->row()->rating;
													$data_rating_project_owner['rating'] = $rating_po;
													$this->load->view('tasks/rating',$data_rating_project_owner);
												}
											?>											
											<br>
											Service Provider Rating:&nbsp; 
											<?php $rating_sp = $this->memberrating->getbyattribute('member_id',$task->row()->assigned_to,'task_id',$task->row()->id); ?>

											<?php 
												if($rating_sp->num_rows() == 0){
													echo "<b>No ratings yet</b>";
												}else{
													$rating_sp = $rating_sp->row()->rating;
													$data_rating_service_provider['rating'] = $rating_sp;
													$this->load->view('tasks/rating',$data_rating_service_provider);
												}
											?>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="card-body">
							<!-- if task has an image -->
							<div class="row row-image <?if(empty($task->row()->image)) echo 'd-none'?>">
								<div class="col-md-12">
									<p class="card-image card-text">
										<img class="img-fluid" src="<?=!empty($task->row()->image) ? $task->row()->image:'https://cdn.vnoc.com/servicechain/default-image-photo.png'?>" style="width:100%;">
									</p>
								</div>
							</div>
							<!-- end if task has an image -->							
							<div class="row row-description">
								<div class="col-md-12">
									<h6>Description:</h6>
									<p class="card-text">
									<?php echo stripcslashes($task->row()->description)?>
									</p>
								</div>
							</div>
							<hr>
							<div class="row">
								<?php if ($task->row()->legal_file !=null):?>
								<div class="col">									
									<h6>Legal File:</h6>
									<p class="card-text">
									<?php
										$file = str_replace("/uploads/tasks/","",$task->row()->legal_file);
										$file_download = $this->config->item('main_url')."/content/tasks/$file"; 
									?>
									<a href="<?php echo $file_download ?>"><?php echo $file?></a>
									</p>									
								</div>
								<?php endif?>
								<div class="col">
									<?php if ($task->row()->goal_date !=null):?>
									<h6>Goal Date:</h6>
									<p class="card-text">
									<?php echo $task->row()->goal_date?>
									</p>
									<?php endif?>
								</div>
								<div class="col">
									<h6>Number of Applications:</h6>
									<p class="card-text">
									<?php echo $this->taskapplicationsdata->getcountbyattribute('task_id',$task->row()->id) ?>
									</p>
								</div>								
							</div>
							<hr>
							<div class="row">
								<div class="col-md-6">
									<?php if ($task->row()->verification !=null):?>
									<h6>Verification:</h6>
									<p class="card-text">
									<?php echo stripcslashes($task->row()->verification)?>
									</p>
									<?php endif?>
								</div>
								<div class="col-md-6">
									<h6>Earn:</h6>
									<?php if($task->row()->status == 'completed'): ?>
										<?php
											$assigned_to = $task->row()->assigned_to; 
											$user_assigned = $this->membersdata->getbyattribute('id',$assigned_to); 
										?>
										<div class="upp">
											<b>
												<a href="/profile/<?php echo $user_assigned->row()->username?>" class="user-info " title="View Profile">
												<?php if ($user_assigned->row()->profile_image == null):?>
												<img class="user-profile-photo" src="http://cdn.vnoc.com/servicechain/user-default.png">
												<?php else:?>
												<img class="user-profile-photo" src="<?php echo $user_assigned->row()->profile_image ?>">
												<?php endif;?>
												<?php echo $user_assigned->row()->firstname.' '.$user_assigned->row()->lastname?>
												</a>
											</b>
										</div>
										<br>
										<div class="alert alert-success" role="alert">
											<small><b>
												Successfully received&nbsp;<br>
												<?php $query = $this->taskcontributionsdata->getbyattribute('task_id',$task->row()->id);?>
												<?php if ($query->num_rows() > 0):?>
													<?php foreach ($query->result() as $rowt):?>
														<a href="<?php echo $this->config->item('etherscan_'.$rowt->network)?>tx/<?php echo $rowt->trans_id?>" target="_blank">

															<?php if ($rowt->token_currency == 'SCESH'):?>
																<span class="badge badge-pill badge-info mb-1"> <?php echo $rowt->token_amount?> <?php echo $this->config->item('servicechain_token')?></span>	
															<?php else:?>
																<span class="badge badge-pill badge-secondary mb-1"> <?php echo $rowt->token_amount?> USDC</span>
															<?php endif?>
														</a> 
													<?php endforeach;?>
													&nbsp;<br>for this task.
												<?php endif?>
											</b> </small>
										</div>

									<?php else:?>
										<?php if ($task->row()->payment == 'equity'):?>
											<span class="badge badge-pill badge-info"><?php echo $task->row()->esh_value?> ESH</span>
										<?php endif; ?>
										<?php if ($task->row()->payment == 'cash'):?>
											<span class="badge badge-pill badge-primary"> <?php echo $task->row()->cash_value?> USDC</span>	
										<?php endif; ?>
										<?php if ($task->row()->payment == 'cash/equity'):?>
											<span class="badge badge-pill badge-primary"> <?php echo $task->row()->cash_value?> USDC</span>	
											&nbsp;<span class="badge badge-pill badge-info"> <?php echo $task->row()->esh_value?> ESH</span>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
							<hr>
							<div class="row">
								<div class="col-md-8">
									<small>
									Posted On&nbsp;&nbsp;<i class="far fa-calendar"></i>&nbsp;<?php echo date('M j Y', strtotime($task->row()->date_created));?>
										<div class="upp">
											<b>
												<a href="/project-owner/profile/<?php echo $user->row()->username?>" class="user-info " title="View Profile">
												<?php if ($user->row()->profile_image == null):?>
												<img class="user-profile-photo" src="http://cdn.vnoc.com/servicechain/user-default.png">
												<?php else:?>
												<img class="user-profile-photo" src="<?php echo $user->row()->profile_image ?>">
											<?php endif;?>
												<?php echo $user->row()->firstname.' '.$user->row()->lastname?>
												</a>
											</b>
										</div>
									</small>									
								</div>
								<div class="col-md-4 text-right">
								   <?php if ($this->session->userdata('logged_in')):?>
								        
								          <?php if ($this->session->userdata('page')=='contractor'):?>
								   
                                           <?php if ($task->row()->assigned_to != null):?>
                                                <?php if ($task->row()->assigned_to == $this->session->userdata('userid')):?>
                                                     <a href="javascript:void(0)" class="btn btn-secondary btn-apply" disabled>You are assigned to this task</a>
                                                  <?php else:?>
                                                     <a href="javascript:void(0)" class="btn btn-secondary btn-apply" disabled>Assigned to <?php echo $this->membersdata->getinfobyid('firstname',$task->row()->assigned_to).' '.$this->membersdata->getinfobyid('lastname',$task->row()->assigned_to) ?></a>
                                                <?endif?>
                                           
                                           <?php else:?>
                                          
                                                  <?php if ($this->taskapplicationsdata->checkexist('task_id',$task->row()->id,'userid',$this->session->userdata('userid')) ===false):?>
                        									    <a href="javascript:applytask(<?php echo $task->row()->id?>);" class="btn btn-danger btn-apply">Apply</a>
                        									    <?php else:?>
                                                <a href="javascript:void(0)" class="btn btn-secondary btn-apply" disabled>You have successfully applied for this service task</a>    
                        									<?php endif?>
                                           
                                           <?endif?>
                                           
                                        <?php endif?>   
								
									<?php else:?>
									<a href="/login" class="btn btn-danger btn-apply">Apply</a>
								  <?php endif?>	
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<input type="hidden" name="task_details_id" id="task_details_id" value="<?php echo $task->row()->id?>">

<div class="modal" tabindex="-1" role="dialog" id="applyTaskModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Why do you want to apply for this task?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div>
			<div class="modal-body">
				<?php $this->load->view('tasks/apply_form')?>
			</div>				
		</div>
	</div>
</div>




<?php $this->load->view('dashboard/footer');?>	