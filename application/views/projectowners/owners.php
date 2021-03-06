<?php
	if ($query->num_rows() > 0){
		foreach ($query->result() as $row){
?>
<li class="poclist media owner">							
	<div class="media-body">
		<div class="row text-center r-gutters">
			<div class="col-md-3 align-middle align-self-center">
				<a target='_blank' href="/project-owner/profile/<?php echo $row->username?>" class="user-info "title="View Profile">
				<img src="<?=empty($row->profile_image)?'http://cdn.vnoc.com/servicechain/user-default.png':$row->profile_image;?>" class="poc-image rounded-circle" alt="..." height="48" width="48">
				<?php
					$name = !empty($row->firstname)?$row->firstname.' '.$row->lastname : $row->username;
				?>
				<h6 class="poc-name">
						<?=ucwords($name)?>
				</h6>
				</a>
			</div>
			<div class="col-md-3 align-middle align-self-center">
				<div class="alert alert-light btn-block">
					<span class="poc-desc">Joined</span><br><span class="poc-value">[&nbsp;<?=date('M j, Y', strtotime($row->signup_date))?>&nbsp;]</span>
				</div>
			</div>
			<div class="col-md-3 align-middle align-self-center">
				<a href="" class="alert alert-light btn-block">
					<span class="poc-desc">Projects</span><br><span class="poc-value">(<?=$row->total_projects?>)</span>
				</a>
			</div>
			<div class="col-md-3 align-middle align-self-center">
				<a href="" class="alert alert-light btn-block">
					<span class="poc-desc">Tasks</span><br><span class="poc-value">(<?=$row->total_tasks?>)</span>
				</a>
			</div>
		</div>
	</div>
</li>
<?php
		}
	}
?>
<script>
last_page = <?=$pages_count?>;
current_page = <?=$current_page?>+1;
</script>