
<?php
	if ($query->num_rows() > 0){
		foreach ($query->result() as $row){
			?>
			<a href="/project/details/<?=$row->id?>/<?=$row->slug?>" class="list-group-item aprolink project">
				<div class="row">
					<div class="col-md-8">
						<div class="proj-title">
							<?=$row->title?>
						</div>
						<div class="asstats">
							<?php
								if($row->status=='new'){
							?>
							<span class="stats-not-active stat-new badge badge-secondary">New</span>
							<?php
								}else if($row->status=='in progess'){
							?>
							<span class="stats-not-active stat-in-progess badge badge-secondary">In Progess</span>
							<?php
								}else if($row->status=='completed'){
							?>
							<span class=" stat-completed badge badge-success">Completed</span>
							<?php
								}
							?>
						</div>
					</div>
					<div class="col-md-4 text-right">
						<small class="prodate">
							<i aria-hidden="true" class="far fa-calendar-alt"></i>
							<?=date('M j, Y', strtotime($row->date_created))?>
						</small>
					</div>
				</div>
			</a>
			<?php
		}
	}else{
?>

				<!--<div class="alert alert-warning" role="alert" id="no-project-error">
                       
                 </div>-->
                 
<?php
	}
?>
