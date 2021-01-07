<style>
.projtop h6 {
    color: #666;
}
.pro-date-stats-2 {
	display: none;
}
@media (max-width: 575.98px) {
	.pro-date-stats-1 {
		display: none;
	}
	.pro-date-stats-2 {
		display: block;
		width: 100%;
	}
	.projstats .badge-info {
	    background: #C69C6D;
	    padding: 0.5rem 0.8rem;
	}
}
.projlist-image {
	background: #eaeaea;
}
</style>
<?php  if ($query->num_rows() > 0):?>
	<?php foreach ($query->result() as $row):?>
	<div class="projtop mb-2">
					<div class="media">
                        <?php if ($row->icon_image == null):?>					
							<img src="https://cdn.vnoc.com/servicechain/project-default.png" class="projlist-image mr-3 border p-1 rounded" alt="<?php echo $row->title?>" width="64" width="64">
							<?php else:?>
							<img src="<?php echo $row->icon_image?>" class="projlist-image mr-3 border p-1 rounded" alt="<?php echo $row->title?>" width="64" width="64">
						<?php endif?>
						<div class="media-body">
							<h5 class="mt-0"><a href="/project/details/<?php echo $row->id?>/<?php echo $row->slug?>"><?php echo $row->title?></a></h5>
							<h6><?php echo $row->title?></h6>
							<div class="projowner">
								<a href="/project-owner/profile/<?php echo $row->username?>">
								<?php if ($row->profile_image == null):?>
									<img class="proj-profile-photo rounded-circle" src="https://cdn.vnoc.com/servicechain/user-default.png" width="24" height="24">
									<?php else:?>
									<img class="proj-profile-photo rounded-circle" src="<?php echo $row->profile_image?>" width="24" height="24">
								<?php endif?>
								<small><?php echo $row->firstname.' '.$row->lastname?></small>
								</a>
							</div>
							<div class="pro-date-stats-2">
								<div class="projdate mb-1">
									<small>Created: <?php echo $row->mydate?></small>
								</div>
								<span class="projstats mt-1">
								<?php if ($row->status=='new'):?>
										<span class="stat-new badge badge-info">New</span>
								<?php endif?>
								
								    <?php if ($row->status=='in progress'):?>
										<span class="stat-in-progess badge badge-secondary">In Progess</span>
								<?php endif?>
								
									<?php if ($row->status=='completed'):?>
										<span class="stat-completed badge badge-success">Completed</span>
								<?php endif?>
									
								</span>						
								<span class="projtaskcount mt-1">
									<span class="badge badge-pill badge-secondary"><b><?php echo $row->count_tasks?></b>&nbsp;Tasks</span>
								</span>
							</div>
						</div>
						<div class="ml-3 text-right pro-date-stats-1">
    							<div class="projstats">
    							<?php if ($row->status=='new'):?>
    									<span class="stat-new badge badge-info">New</span>
    							<?php endif?>
							
							    <?php if ($row->status=='in progress'):?>
    									<span class="stat-in-progess badge badge-secondary">In Progess</span>
    							<?php endif?>
							
								<?php if ($row->status=='completed'):?>
    									<span class="stat-completed badge badge-success">Completed</span>
    							<?php endif?>
								
							</div>
							<div class="projdate mt-1">
								<small>Created: <?php echo $row->mydate?></small>
							</div>
							<div class="projtaskcount mt-1">
								<span class="badge badge-pill badge-secondary"><b><?php echo $row->count_tasks?></b>&nbsp;Tasks</span>
							</div>
						</div>						
					</div>					
				</div>
	<?php endforeach;?>
<?php endif?>