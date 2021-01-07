<?php if ($tasks->num_rows() > 0): ?>
	<?php foreach ($tasks->result() as $row): ?>
		<li data-draggable="item" id="<?php echo $row->id?>">
			<div class="media">
				<div class="media-body">
					<div class="mytask-inner">
						<div class="float-right">
							<div class="dropdown dropdown-kanban-custom">
								<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-secondary btn-sm dropdown-toggle btn-dropdown-custom-kanban py-1 px-2">
									<i class="fas fa-cog"></i>
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" target="_blank" href="/task/updates/<?php echo $row->id?>"><i class="fas fa-paste mr-1"></i>Task Updates</a>
								    <?php if (($row->status == 'for approval') || ($row->status == 'completed')):?>
								    	 <?php if (($row->payment == 'cash') || ($row->payment == 'cash/equity')):?>
            						     <a class="dropdown-item" href="javascript:setpdetails(<?php echo $row->id?>)"><i class="fas fa-file-invoice mr-1"></i> Payment Details</a>
            						    <?php endif?>
            						<?php endif?>
            						<?php if ($row->status == 'completed'):?>
            							<?php if ($this->taskcontributionsdata->checkexist('task_id',$row->id)===true):?>
            							<a class="dropdown-item" href="javascript:gettransaction(<?php echo $row->id?>)"><i class="fas fa-th mr-1"></i> Blockhain Transaction</a>
            							<?php endif;?>
            						<?php endif?>
								</div>
							</div>
						</div>
						<div class="d-block  mb-2">
							<span class="bg-light-title px-1 text-white text-uppercase meta-badge-project-title d-inline-block">
								<small>
									<?php echo $row->project_title?>
								</small>
							</span>
						</div>
						<div class="mytask-title mb-3">
							<?php echo $row->title?>
						</div>
						<ul class="list-inline mb-3">
							<?php if ($row->payment == 'equity'):?>
								<li class="list-inline-item">
									<img src="https://cdn.vnoc.com/icons/token-service-chain.png" alt="" class="mr-1 mt-n1" width="20" height="">
									<small class="meta-value-num-equity">
										<?php echo $row->esh_value?> ESH
									</small>
								</li>
							<?php endif?>
							<?php if ($row->payment == 'cash'):?>
								<li class="list-inline-item">
									<img src="https://cdn.vnoc.com/icons/coins.png" alt="" class="mr-1 mt-n1" width="18" height="">
									<small class="meta-value-num-equity">
										<?php echo $row->cash_value?> USDC
									</small>
								</li>
							<?php endif?>
							<?php if ($row->payment == 'cash/equity'):?>
								<li class="list-inline-item">
									<img src="https://cdn.vnoc.com/icons/coins.png" alt="" class="mr-1 mt-n1" width="18" height="">
									<small class="meta-value-num-equity">
										<?php echo $row->cash_value?> USDC
									</small>
								</li>
								<li class="list-inline-item">
									<img src="https://cdn.vnoc.com/icons/token-service-chain.png" alt="" class="mr-1 mt-n1" width="20" height="">
									<small class="meta-value-num-equity">
										<?php echo $row->esh_value?> ESH
									</small>
								</li>
							<?php endif?>		
						</ul>
						<div class="float-right">
							<?php if ($row->profile_image == null):?>
								<img src="https://cdn.vnoc.com/servicechain/user-default.png" class="rounded-circle mt-n2 img-cover-circle" alt="<?php echo $row->firstname?> <?php echo $row->lastname?>" height="40" width="40">
							<?php else:?>
								<img src="<?php echo $row->profile_image?>" class="rounded-circle mt-n2 img-cover-circle" alt="<?php echo $row->firstname?> <?php echo $row->lastname?>" height="40" width="40" title="<?php echo $row->firstname?> <?php echo $row->lastname?>">
							<?php endif?>
						</div>
						<div class="mytask-date bg-dark-red text-white d-inline-block px-1 py-1">
							Goal Date <i class="far fa-calendar mr-1 ml-1" aria-hidden="true"></i> <?php echo $row->goal_date?>
						</div>
					</div>
				</div>
			</div>
		</li>	
	<?php endforeach;?>
<?php endif?>