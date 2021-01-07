<?php if ($tasks->num_rows() > 0): ?>
<div class="row">
								<div class="col-lg-12 mb-3">
									<div class="card projbox-card">
										<div class="card-header border-bottom-0">
											<a href="/project-owner/profile/<?php echo $owner->row()->username?>" class="media media-userlink-projbox">
											    <?php if ($owner->row()->profile_image == null):?>
												   <img class="mr-3 rounded-circle img-projbox" src="https://cdn.vnoc.com/icons/default-80x80.jpg" alt="<?php echo $owner->row()->firstname.' '.$owner->row()->lastname?>">
												<?php else:?>
												<img class="mr-3 rounded-circle img-projbox" src="<?php echo $owner->row()->profile_image?>" alt="">
												<?php endif?>
												<div class="media-body">
													<h5 class="mt-3 mb-0 text-capitalize f-600 project-username"><?php echo $owner->row()->firstname.' '.$owner->row()->lastname?></h5>
													<ul class="list-inline mb-0 projbox-stats-user"> </ul>
												</div>
											</a>
										</div>
										<div class="card-body">
											<ul class="list-unstyled mb-0 ul-item-post">
											<?php foreach ($tasks->result() as $row): ?>
												<li>
													<div class="media">
														<div class="post-user-todo mr-2">
															<div style="width: 21px; height: 21px;margin-top: 11px;"> <span class="pulse-todo"></span> </div>
														</div>
														<div class="media-body">
															<div class="projbox-content-item"> <small class="text-light-gray d-inline-block float-right meta-projbox-date">
																<i class="fas fa-clock mr-1" aria-hidden="true"></i><?php echo date('M j Y g:i a', strtotime($row->date_created));?></small> 
																<a href="/tasks/details/<?php echo $row->id?>/<?php echo url_title($row->title, 'dash', true)?>" class="text-reset text-decoration-none"><?php echo $row->title?></a> 
															</div>
														</div>
													</div>
												</li>
												<?php endforeach;?>
											</ul>
										</div>
									</div>
								</div>
							</div>
	
	
	
	<?php else:?>
	<div class="row">
								<div class="col-lg-12 mb-3">
									<div class="card projbox-card">
										<div class="card-body">
											<div class="main-empty-data-body d-block text-center"> <i class="fas fa-th-list mr-2" aria-hidden="true"></i> No <?php echo ucfirst($status)?> Tasks </div>
										</div>
									</div>
								</div>
							</div>
<?php endif?>