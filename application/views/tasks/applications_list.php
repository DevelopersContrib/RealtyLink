<style>
.project-outermost a:hover {
	text-decoration: none;
}
.project-container {   
    padding: 20px 5px 20px;
}
.user-profile-photo {
	height: 24px;
	width: 24px;
	border-radius: 50%;
}
.badge-pill {
	padding: 1rem;
}
.asstats .badge {
	padding: .5rem 1rem;
}
</style>
<?php if ($tasks->num_rows() > 0): ?>
		<?php foreach ($tasks->result() as $row): ?>
			<div class="col-md-12 project project-outermost pr-4 pl-4">
						<div class="card mb-2">
							<div class="card-header">
								<div class="pt-2 float-left">
									<a href="/tasks/details/<?php echo $row->id?>/<?php echo url_title($row->title, 'dash', true)?>" class="task-link"><?php echo $row->title?>&nbsp;<i class="fas fa-caret-right"></i></a>
								</div>
								<div class="curr float-right">
								    <?php if ($row->payment == 'equity'):?>
										<div class="card-footer-items">
											<span class="badge badge-pill badge-info"> <?php echo $row->esh_value?> ESH</span>	
										</div>
										<?php endif?>
										<?php if ($row->payment == 'cash'):?>
										<div class="card-footer-items">
											<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?> USDC</span>	
										</div>
										<?php endif?>
											<?php if ($row->payment == 'cash/equity'):?>
										<div class="card-footer-items">
											<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?> USDC</span>	
											&nbsp;<span class="badge badge-pill badge-info"> <?php echo $row->esh_value?> ESH</span>	
										</div>
										<?php endif?>
								</div>
							</div>
							<div class="card-body">								
								<div class="row">
									<div class="col-md-8">
									<div class="card-text mb-2"><a href="/project/details/<?php echo $row->project_id?>/<?php echo $row->project_slug?>"><?php echo $row->project_title?></a></div>
										<small>											
											<div class="upp">
												<b>
													<a href="/project-owner/profile/<?php echo $row->username?>" class="user-info "title="View Profile">
													<?php if ($row->profile_image == null):?>
												<img class="user-profile-photo" src="http://cdn.vnoc.com/servicechain/user-default.png">
												<?php else:?>
												<img class="user-profile-photo" src="<?php echo $row->profile_image?>">
											<?php endif;?>
													<?php echo $row->firstname?> <?php echo $row->lastname?>
													</a>
												</b>
											</div>
										</small>
									</div>
									<div class="col-md-4 text-right">
										<small>Posted On&nbsp;&nbsp;<i class="far fa-calendar"></i>&nbsp;<?php echo date('M j Y', strtotime($row->date_created));?></small>
										<div class="asstats mt-2">
										    <?php if ($row->task_status == 'pending'):?>
												<span class="stat-new badge badge-info">Pending</span>
										    <?php endif?>
											<?php if ($row->task_status == 'approved'):?>
												<span class="stat-in-progess badge badge-secondary">Approved</span>
										    <?php endif?>
											<?php if ($row->task_status == 'declined'):?>
												<span class="stat-completed badge badge-success">Declined</span>
										    <?php endif?>	
										</div>
									</div>
									
								</div>								
							</div>
						</div>						
					</div>
		<?php endforeach;?>
<?php endif?>

<?php $this->load->view('taskajax/ajax-task-pagination'); ?>