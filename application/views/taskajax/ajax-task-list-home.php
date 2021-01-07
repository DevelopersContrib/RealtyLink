<style>
a:hover {
	text-decoration: none;
}
.project-outermost a:hover {
	text-decoration: none;
}
.badge-pill {
	padding: .85rem 1rem;
}
.card-footer {
    padding: .75rem 1.25rem .15rem;
}
.title {
    margin-bottom: .5rem;
}
.section-services {
    background: #f2f2f2;
}
.card-footer {
    border-top: none;
    background: #f5efef;
}
.user-info:hover {
	text-decoration: none;
}
.user-profile-photo {
	height: 24px;
	width: 24px;
	border-radius: 50%;
}
.badge-info {
    background-color: #C69C6D;
}
.tasklist-image {
	width: 64px;
	height: 64px;
	background: #eaeaea;
}
.media-left {
    margin-right: .6rem;
}
</style>
<?php if ($tasks->num_rows() > 0): ?>
		<?php foreach ($tasks->result() as $row): ?>
<div class="col-md-12">
	<div class="project">
		<div class="card">
			<div class="card-content">
				<article class="media">
					<div class="media-left">
						<img class="tasklist-image border p-1 rounded "src="https://cdn.vnoc.com/servicechain/tasklist-default-image.png">
					</div>
					<div class="media-content">
						<a href="">
							<h2 class="title is-5"><a href="/tasks/details/<?php echo $row->id?>/<?php echo url_title($row->title, 'dash', true)?>" class="task-link"><?php echo $row->title?></a></h2>
							<h5 class="subtitle is-6"><a href="/project/details/<?php echo $row->project_id?>/<?php echo $row->project_slug?>"><?php echo $row->project_title?></a></h5>
						</a>
						
					</div>
					<div class="media-right">
						<p class="bbs">
							<?php if ($row->payment == 'equity'):?>
								<span class="badge badge-pill badge-info"><?php echo $row->esh_value?> ESH</span>
							<?php endif?>
							<?php if ($row->payment == 'cash'):?>
								<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?> USDC</span>	
							<?php endif?>
							<?php if ($row->payment == 'cash/equity'):?>
								<span class="badge badge-pill badge-secondary"> <?php echo $row->cash_value?> USDC</span>	
								&nbsp;<span class="badge badge-pill badge-info"> <?php echo $row->esh_value?> ESH</span>	
							
							<?php endif?>		
						</p>
					</div>
				</article>
			</div>
			<footer class="card-footer">								
				<p class="col-md-6 card-footer-items">
					<small>
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
					</small>
				</p>
				<p class="col-md-6 card-footer-items text-right">									
					<small>
						Posted On&nbsp;&nbsp;<i class="fas fa-calendar"></i>&nbsp;<?php echo date('M j Y', strtotime($row->date_created));?>									
					</small>								
				</p>
			</footer>
		</div>
	</div>
</div>
		<?php endforeach;?>
		<?php else:?>

		<div class="col-md-12 project">
						<div class="card">
				<div class="lead-camp-title text-center">-- No Tasks Available --</div> 
			</div>								
		</div>

<?php endif?>
<?php /*?><pre><?=$sql?></pre><?php */?>
<?php //$this->load->view('taskajax/ajax-task-pagination'); ?>
<script>
last_page = <?=$pages_count?>;
current_page = <?=$current_page?>+1;
</script>