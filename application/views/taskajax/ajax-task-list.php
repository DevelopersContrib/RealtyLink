<style>
.project-outermost .card-header {
    background: #e9e9e9;
}
.project-outermost a:hover {
	text-decoration: none;
}
.user-profile-photo {
	height: 24px;
	width: 24px;
	border-radius: 50%;
}
.card-text a {
	color: #455B68;
}
.user-info {
	color: #455B68;
}
.badge-info {
    background-color: #C69C6D;
}
</style>

<div class="col-md-8 project project-outermost">
<?php if ($tasks->num_rows() > 0): ?>
<?php foreach ($tasks->result() as $row): ?>
	<div class="card mb-2">
		<div class="card-header">
			<div class="float-left pt-2">
				<a href="/tasks/details/<?php echo $row->id?>/<?php echo url_title($row->title, 'dash', true)?>" class="task-link"><?php echo $row->title?>&nbsp;<i class="fas fa-caret-right"></i></a>
			</div>
			<div class="float-right">
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
			<p class="card-text"><a href="/project/details/<?php echo $row->project_id?>/<?php echo $row->project_slug?>"><?php echo $row->project_title?></a></p>
			<div class="row">
				<div class="col-md-8">
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
					<small>
						Posted On&nbsp;&nbsp;<i class="far fa-calendar"></i>&nbsp;<?php echo date('M j Y', strtotime($row->date_created));?>
					</small>
				</div>
			</div>
		</div>
	</div>
<?php endforeach;?>
<?php endif?>				
</div>

<div class="col-md-4 project project-outermost">
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
	
</div>

<?php $this->load->view('taskajax/ajax-task-pagination'); ?>