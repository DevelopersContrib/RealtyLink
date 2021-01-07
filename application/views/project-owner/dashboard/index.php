<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<!-- breadcrumb -->
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item active" aria-current="page">Dashboard</li>
		</ol>
	</div>
<link rel="stylesheet" href="/assets/css/stats-owner.css">
<style>
.txt-alert {
        position: absolute;
        font-size: 12px;
        color: red;
        bottom: -11px;
        background: #fff;
        padding: 0px 3px 0px 1px;
}
.stats__change{
	display:none;
}
.user-profile-photo {
    height: 24px;
    width: 24px;
    border-radius: 50%;
}
.box-outer {
	background: #ededed;
	border: 1px solid #e0e0e0;
	display: block;
}
.box-outer:hover {
	background: #F7F4F2;
	text-decoration: none;
}
</style>

	<!-- <div class="page-container" style="min-height: 548px;"> -->
	<div class="page-container">
		<div class="container">			
			<div class="row no-gutters">
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/myprojects" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Projects</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$count_projects?></div>
									<div class="stats__caption">Total Projects</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">16%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/myprojects" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Projects</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$new_projects?></div>
									<div class="stats__caption">New</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">10%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/myprojects" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Projects</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$inprogress_projects?></div>
									<div class="stats__caption">In Progress</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">35%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/myprojects" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Projects</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$completed_projects?></div>
									<div class="stats__caption">Completed</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">40%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>				
			</div>
			<div class="row no-gutters">
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/kanban" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Tasks</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$count_tasks?></div>
									<div class="stats__caption">Total Tasks</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">40%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/kanban" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Tasks</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$inprogress_tasks?></div>
									<div class="stats__caption">In Progress</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">40%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/kanban" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Tasks</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$forapproval_tasks?></div>
									<div class="stats__caption">For Approval</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">40%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stats-con">
						<a href="/project-owner/kanban" class="box box-outer">
							<div class="box__header">
								<h3 class="box__header-title">Tasks</h3> 
							</div>
							<div class="box__body">
								<div class="stats stats--main">
									<div class="stats__amount"><?=$completed_tasks?></div>
									<div class="stats__caption">Completed</div>
									<div class="stats__change">
										<div class="stats__value stats__value--positive">40%</div>
										<div class="stats__period">this week</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="row s-gutters">
				<div class="col-md-4">
					<ul class="list-group">
						<li class="list-group-item active">Latest Tasks</li>
						<?php
						if ($latest_query->num_rows() > 0){
							foreach ($latest_query->result() as $row){
						?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-12">
									<?php 
										
									?>
									<div class="lead-name text-truncate"><a target="_blank" href="/tasks/details/<?=$row->id?>/<?=url_title($row->title, 'dash', true);?>"><?=stripslashes($row->title)?></a></div>
									<?php
										if(!empty($row->member_id)){
											$name = empty($row->firstname)?$row->username:$row->firstname.' '.$row->lastname;
									?>
									<div class="lead-email"><small> <a target="_blank" href="/profile/<?=$row->username?>"><img class="user-profile-photo" src="<?=empty($row->profile_image)?'http://cdn.vnoc.com/servicechain/user-default.png':$row->profile_image;?>"> <?=$name?></a></small></div>
									<?php
										}
									?>
								</div>
								<!--<div class="col-md-3 text-right">
									<div class="lead-count">4</div>
								</div>-->
							</div>
						</li>
						<?php
							}
						}else{
						?>
						<li class="list-group-item lmh">
							<div class="row">
								<div class="col-md-12">
									<div class="lead-camp-title text-center">-- No Tasks Available --</div> 
								</div>								
							</div>
						</li>
						<?php
						}
						?>
					</ul>
				</div>
				<div class="col-md-4">
					<ul class="list-group">
						<li class="list-group-item active">For approval</li>
						<?php
						if ($for_approval_query->num_rows() > 0){
							foreach ($for_approval_query->result() as $row){
						?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-12">
									<div class="lead-name text-truncate"><a target="_blank" href="/project-owner/project/<?=$row->project_id?>/<?=url_title($row->title, 'dash', true);?>/forapp"><?=stripslashes($row->title)?></a></div>
									<?php
										if(!empty($row->member_id)){
											$name = empty($row->firstname)?$row->username:$row->firstname.' '.$row->lastname;
									?>
									<div class="lead-email"><small> <a target="_blank" href="/profile/<?=$row->username?>"><img class="user-profile-photo" src="<?=$row->profile_image?>"> <?=$name?></a></small></div>
									<?php
										}
									?>
								</div>
								<!--<div class="col-md-3 text-right">
									<div class="lead-count">4</div>
								</div>-->
							</div>
						</li>
						<?php
							}
						}else{
						?>
						<li class="list-group-item lmh">
							<div class="row">
								<div class="col-md-12">
									<div class="lead-camp-title text-center">-- No Tasks Available --</div> 
								</div>								
							</div>
						</li>
						<?php
						}
						?>
					</ul>
				</div>
				<div class="col-md-4">
					<ul class="list-group">
						<li class="list-group-item active">Applications</li>
						<?php
						if ($appplication_query->num_rows() > 0){
							foreach ($appplication_query->result() as $row){
						?>
						<li class="list-group-item">
							<div class="row">
								<div class="col-md-12">
									<div class="lead-camp-title"><a target="_blank" href="/project-owner/project/<?=$row->project_id?>/<?=url_title($row->title, 'dash', true);?>/withapp"><?=stripslashes($row->title)?></a></div>
									<?php
										if(!empty($row->member_id)){
											$name = empty($row->firstname)?$row->username:$row->firstname.' '.$row->lastname;
									?>
									<div class="lead-camp-desc"><small><a target="_blank" href="/profile/<?=$row->username?>"><img class="user-profile-photo" src="<?=$row->profile_image?>"> <?=$name?></a></small></div>
									<?php
										}
									?>
								</div>								
							</div>
						</li>
						<?php
							}
						}else{
						?>
						<li class="list-group-item lmh">
							<div class="row">
								<div class="col-md-12">
									<div class="lead-camp-title text-center">-- No applications --</div> 
								</div>								
							</div>
						</li>
						<?php
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	

<?php $this->load->view('project-owner/dashboard/footer');?>
