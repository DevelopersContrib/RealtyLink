<?php if ($tasks->num_rows() > 0): ?>
	<?php foreach ($tasks->result() as $row): ?>
	             <a href="/tasks/details/<?php echo $row->id?>/<?php echo url_title($row->title, 'dash', true)?>" class="list-group-item aprolink project">
						<div class="row">
							<div class="col-md-9">
								<div class="task-title proj-title mb-0">
									<?php echo $row->title?>
								</div>
								<div class="subtask-title mb-2">
									<small><?php echo stripcslashes($row->description)?></small>
								</div>
								<div class="asstats">
								    <?php if ($row->status=='new'):?>
								    	<span class="stats-not-active stat-new badge badge-info mb-1">New</span>
								    <?php endif?>
									<?php if ($row->status=='in progress'):?>
								    	<span class="stat-in-progess badge badge-secondary mb-1">In Progess</span>
								    <?php endif?>
									<?php if ($row->status=='for approval'):?>
								    	<span class="stat-in-progess badge badge-secondary mb-1">For Approval</span>
								    <?php endif?>
									<?php if ($row->status=='completed'):?>
								    	<span class="stat-completed badge badge-success mb-1">Completed</span>
								    <?php endif?>
									
									
								</div>
							</div>
							<div class="col-md-3 text-right">
								<small class="prodate">
									<i aria-hidden="true" class="far fa-calendar-alt"></i>&nbsp;<?php echo date('M j Y', strtotime($row->date_created));?>
								</small>
								<div class="card-footer-items mt-2">
								     <?php if ($row->payment == 'equity'):?>
								     	<span class="badge badge-pill badge-info"><?php echo $row->esh_value?><?php echo $this->config->item('servicechain_token')?></span>	
								     <?php endif?> 
								     <?php if ($row->payment == 'cash'):?>
								     	<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?>USDC</span>		
								     <?php endif?>
								     <?php if ($row->payment == 'cash/equity'):?>
									<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?>USDC</span>	
									&nbsp;<span class="badge badge-pill badge-info"><?php echo $row->esh_value?> <?php echo $this->config->item('servicechain_token')?></span>
									<?php endif?>	
								</div>
							</div>
						</div>
					</a>
	<?php endforeach;?>
	<?php else:?>
	<div class="main-empty-data-body d-block text-center mt-2"> <i class="fas fa-th-list mr-2" aria-hidden="true"></i> No Tasks Available</div>
<?php endif?>

<?php $this->load->view('taskajax/ajax-task-pagination'); ?>