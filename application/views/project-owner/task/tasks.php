<style>
.alert-success {
    color: #034d14;
    background-color: #F9F0E7;
    border-color: #F9F0E7;
}
.asstats .badge {
	display: block;
	margin-bottom: 5px;
}
</style>
<?php
	if ($query->num_rows() > 0){
		$xid = time();
		foreach ($query->result() as $row){
			$xid++;
?>
<div id="" data-id="<?=$row->id?>" class="task-box task-box<?=$row->id?> col-md-12 project mb-3">
	<div class="card">
		<div class="card-content project-content-height">
			<article class="media">
				<div class="media-left">
					<a href="javascript:;">
						<figure class="image is-48x48 img-rounded"><img class="img-thumbnail" src="http://cdn.vnoc.com/cowork/project-default-icon.png" alt=""></figure>
					</a>
				</div>
				<div class="media-content"> 
					<div class="float-right">
						<div class="tasbutton text-right">
							<button data-id="<?=$row->id?>" class="btn btn-sm btn-outline-success edit-task" data-toggle="tooltip" data-placement="top" title="Edit"> <i class="fas fa-pencil-alt" aria-hidden="true"></i> </button>
							<button data-id="<?=$row->id?>" class="btn btn-sm btn-outline-danger delete-task" data-toggle="tooltip" data-placement="top" title="Delete"> <i class="fas fa-trash-alt" aria-hidden="true"></i> </button>
						</div>
						<div class="asstats mt-3 text-right">
							<?php
								if($row->status=='new'){
							?>
							<span class="stat-new badge badge-pill badge-info">New</span>
							<?php
								}else if($row->status=='in progess'){
							?>
							<span class="stat-in-progess badge badge-pill badge-secondary">In Progess</span>
							<?php
								}else if($row->status=='for approval'){
							?>
							<span class="stat-for-approval badge badge-pill badge-danger">For Approval</span>
							<a href="/task/updates/<?=$row->id?>" target="_blank"><span class="stat-for-approval badge badge-pill badge-warning"><i class="far fa-comments"></i>&nbsp;View Updates (<?php echo $this->taskupdatesdata->getcountbyattribute('task_id',$row->id)?>)</span></a>
							<a  href="javascript:;" class="btnSetCompleted" data="<?php echo $row->id?>"><span class="stat-for-approval badge badge-pill badge-success"><i class="far fa-thumbs-up"></i>&nbsp;Set to completed</span></a>
							<?php
								}else if($row->status=='completed'){
							?>
							<span class="stat-completed badge badge-pill badge-success">Completed</span>
							<a href="/task/updates/<?=$row->id?>" target="_blank"><span class="stat-for-approval badge badge-pill badge-warning">View Updates (<?php echo $this->taskupdatesdata->getcountbyattribute('task_id',$row->id)?>)</span></a>
							
							<?php
								}
							?>
							<!--<span class="stat-updates badge badge-light">(5) Updates</span>-->
						</div>						
						<!--<div class="tascat mt-2 text-right">
							<span class="badge badge-pill badge-dark">Category Name</span>
						</div>-->
						
					</div>
					
					<a href="/tasks/details/<?=$row->id?>/<?=url_title($row->title, 'dash', true)?>">
						<h1 class="title is-5"><?=stripslashes($row->title)?> </h1>
						<h5 class="subtitle"><?=$row->description?></h5>	
						
					</a>
					<?php if ($row->status == 'completed'):?>
						  <div class="row">
						  <div class="col-md-12">
							<div class="alert alert-success" role="alert">
                        Transaction(s):<br> 
                           <?php $query = $this->taskcontributionsdata->getbyattribute('task_id',$row->id);?>
                           <?php if ($query->num_rows() > 0):?>
                           		<?php foreach ($query->result() as $rowt):?>
                           		<a href="<?php echo $this->config->item('etherscan_'.$rowt->network)?>tx/<?php echo $rowt->trans_id?>" target="_blank"><span class="convalues"><?php echo $rowt->trans_id?></span></a>
                           		&nbsp; for &nbsp;
                           		<?php if ($rowt->token_currency == 'SCESH'):?>
                           		     	<span class="badge badge-pill badge-info"> <?php echo $rowt->token_amount?> <?php echo $this->config->item('servicechain_token')?></span>	
                           			<?php else:?>
                           			<span class="badge badge-pill badge-secondary"> <?php echo $rowt->token_amount?> USDC</span>
                           		<?php endif?>
                           		<br>
                           		<?php endforeach;?>
                           <?php endif?>
                           
                         </div>
										
								</div>		
						</div>
						   <?php endif?>					
					<div class="row">						
						<div class="col-md-12">
						   
							<div class="tasdate">
								<i class="fas fa-calendar" aria-hidden="true"></i>&nbsp;
								<?=date('M j, Y', strtotime($row->goal_date))?>
							</div>
							<div class="assto mb-1">
								
								<?php
								if(!empty($row->assigned_to)){
							?>
							Assigned To:
							<a target='_blank' href="/profile/<?php echo $row->username?>" class="user-info "title="View Profile">
								<div class="apptasklists media">
									<img src="<?=empty($row->profile_image)?'http://cdn.vnoc.com/servicechain/user-default.png':$row->profile_image;?>" class="mr-2 rounded-circle" alt="" height="32" width="32">
									<div class="media-body">
										<h6 class="taskuser mt-2"><?=$row->firstname.' '.$row->lastname?></h6>
									</div>
								</div>
								<?php
								}
							?>
							</a>
							</div>
							
							
						</div>
						<?php
							if(empty($row->assigned_to)){
								$taskApp = $this->taskapplicationsdata->getapplicants('task_id',$row->id);
								if ($taskApp->num_rows() > 0){
							?>
								<div class="col-md-12 mb-3 mt-3">
									<!-- -->
									<div class="accordion" id="accordionTaskApp">
										<div class="card apptask-outer">
											<div class="card-header" id="headingTwo">
												<h2 class="mb-0">
												  <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse<?=$xid;?>">
													Service Provider Applicant<?=$taskApp->num_rows()>1?'s':''?> (<?=$taskApp->num_rows();?>)
												  </button>
												</h2> 
											</div>
											<div id="collapse<?=$xid;?>" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionTaskApp">
												<div class="card-body">
													<?php
														foreach ($taskApp->result() as $app){
													?>
													<div class="apptasklist media">
													   <a href="/profile/<?php echo $app->username?>" target="_blank">   
														 <img src="<?=empty($app->profile_image)?'http://cdn.vnoc.com/servicechain/user-default.png':$app->profile_image;?>" class="mr-2 rounded-circle" alt="" height="40" width="40">
														</a>
														<div class="media-body">
														     <a href="/profile/<?php echo $app->username?>" target="_blank">
															<?php
																$name = empty($app->firstname)?$app->username:$app->firstname.' '.$app->lastname;
															?>
															<h6 class="taskuser mt-0"><?=$name?></h6>
															</a>
															<small>
																<div class="dateapplied"><i class="far fa-calendar"></i>&nbsp;<?=date('M j, Y', strtotime($app->date_requested))?></div>
																<!--<div class="taskappmessage">
																	<i class="far fa-sticky-note"></i>&nbsp;Lorem ipsum dolor set amet.
																</div>-->
															</small>
														</div>
														<div class="ml-3 text-right">
															<div class="btn-group-toggle" data-toggle="buttons">
																<!--<label class="btn btn-success btn-sm active">
																	<input type="checkbox" checked autocomplete="off">
																	<i class="far fa-hand-pointer"></i>&nbsp;Select
																</label>-->
																<?php
																	if($app->status=='pending'){
																?>
																<a data-application="<?=$app->id?>" data-id="<?=$app->userid?>" href="javascript:;" class="btn btn-danger btn-sm btn-approve">
																	<i class="far fa-hand-pointer"></i>&nbsp;Approve
																</a>
																<?php
																	}else if($app->status=='approved'){
																?>
																	<label class="btn btn-success btn-sm active">
																		<i class="fas fa-thumbs-up"></i>&nbsp;Approved
																	</label>
																<?php
																	}else if($app->status=='declined'){
																?>
																	<label class="btn btn-info btn-sm active">
																		<i class="fas fa-thumbs-down"></i>&nbsp;Declined
																	</label>
																
																<?php
																	}
																?>
															</div>
															<div class="button-view-message mt-2">
																<a data-name="<?=$name?>" data-img="<?=empty($app->profile_image)?'http://cdn.vnoc.com/servicechain/user-default.png':$app->profile_image;?>" href="javascript:;" class="btn btn-warning btn-sm btn-view-message">
																	<i class="far fa-eye"></i>&nbsp;View Message
																</a>
																<input class="message" type="hidden" value="<?=stripslashes($app->message);?>" />
															</div>
														</div>
													</div>
													<?php
														}
													?>
													
												</div>
											</div>
										</div>
									</div>
									<!-- -->
								</div>
								<?php
								}
							}
							?>
					</div>
				</div>
			</article>									
		</div>								
	</div>
</div>

<?php
		}
?>

<?php
	}else{
?>

				<!--<div class="alert alert-warning" role="alert" id="no-project-error">
                       
                 </div>-->
                 
<?php
	}
?>