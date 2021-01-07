<?php  if ($query->num_rows() > 0):?>
	<?php foreach ($query->result() as $row):?>
					<!-- project-container -->
					<div class="project-container mb-3" id="<?php echo $row->id?>" data="<?php echo $row->slug?>" style="cursor:pointer;">
						<div class="row mb-2">
							<div class="col-md-3">
							   <?php if (($row->icon_image !=null) && ($row->icon_image !='')):?>
							   <img class="img-fluid" src="<?php echo $row->icon_image?>" width="150">
							     <?php else:?>
								<img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain-1.png" width="150">
							  <?php endif?>	
							</div>
							<div class="col-md-6 text-center">
								<h4>
									<?php echo $row->title?>
								</h4>
								<!--  <small><span class="badge badge-danger col-md-3"><?php echo $row->status?></span></small>-->
								<?php if ($this->projectcontractdata->hascontract($row->id,'data')===false):?>
							    	<a class="btn btn-sm btn-warning btncreatedata<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="Create data contract">Create data contract</a>
							        <?php else:?>
							        <?php if ($this->projectcontractdata->hascontract($row->id,'dan')===false):?>
							        <a class="btn btn-sm btn-warning btncreatedan<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="Create dan contract">Create dan contract</a>
							        <?php endif?>
							        
							    <?php endif?>  
							</div>							
							<div class="col-md-3 text-right">
							
								<a class="btn btn-sm btn-info btnstatus<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="change status"><i class="fas fa-cog"></i></a>
								<a class="btn btn-sm btn-warning btnedit<?php echo $row->id?>"  id="<?php echo $row->id?>" title="edit"><i class="fas fa-edit"></i></a>
								<a class="btn btn-sm btn-danger btndelete<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="delete"><i class="fas fa-trash"></i></a>
							</div>
						</div>
						<div class="row no-gutters">
							 <?php if ($this->projectcontractdata->hascontract($row->id,'dan')):?>
							<div class="col-md-12">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row align-self-center">
											<div class="col-md-6 convalue">
												<b>Contract:</b><br>
												<span class="convalues"><?php echo $this->projectcontractdata->getinfo('address','project_id',$row->id,'contract_type','dan')?></span>
											</div>
											<div class="col-md-6 align-self-center">
												<div class="row align-self-center">
													<div class="col-md-8">					
														<b>&nbsp;ESH Balance:</b>&nbsp;<?php echo $this->cryptoapi->geteshbalance($row->id)?>
														<span><img src="https://d2qcctj8epnr7y.cloudfront.net/images/jayson/icons/currency-esh-2.png" height="30"></span>
													</div>
													<div class="col-md-4 text-right">
													   <?php if ($this->cryptoapi->geteshbalance($row->id)==0):?>
    													     <?php if ($this->mintransdata->checkexist('project_id',$row->id,'status','pending')===false):?>
    																<a id="<?php echo $row->id?>" class="btn btn-sm btn-warning btnmintesh<?php echo $row->id?>" title="buy esh">MINT SCESH</a>
    																<?php else:?>
    																<a id="javascript:void(0)" class="btn btn-sm btn-secondary">MINTING SCESH</a>
    														<?php endif?>
    														<?php else:?>
    														<?php 
    														   $id = $row->id;
    														   $this->db->query("Update mint_transactions set `status`='done' where project_id='$id'");
    														?>
														<?php endif?>
													</div>
												</div>
											</div>
										</div>
									</li>																	
								</ul>
							</div>			
							<?php endif;?>						
							<div class="col-md-3 prostats">
								<ul class="list-group">
									<li class="list-group-item">
										<span class="statsbox mb-1">
											<span class="badge badge-primary">
												<b><?php echo $row->count_tasks?></b>
												Tasks
											</span>
										</span>
									</li>
								</ul>
							</div>
							<div class="col-md-3 prostats">
								<ul class="list-group">
									<li class="list-group-item">
										<span class="statsbox mb-1">
											<span class="badge badge-secondary">
												<b><?php echo $row->count_applications?></b>
												Applications
											</span>
										</span>
									</li>
								</ul>
							</div>
							<div class="col-md-3 prostats">
								<ul class="list-group">
									<li class="list-group-item">
										<span class="statsbox mb-1">
											<span class="badge badge-info">
												<b><?php echo $row->count_approval?></b>
												For Approval
											</span>
										</span>
									</li>
								</ul>
							</div>
							<div class="col-md-3 prostats">
								<ul class="list-group">
									<li class="list-group-item">
										<span class="statsbox mb-1">
											<span class="badge badge-success">
												<b><?php echo $row->count_done?></b>
												Done
											</span>
										</span>
									</li>
								</ul>
							</div>
							<div class="col-md-12 mt-2 pt-2 col-bdr">
								<div class="row">
									<div class="col-md-8">
										<div class="asstats">
											<span class="<?php if ($row->status != 'new') echo 'stats-not-active'?> stat-new badge badge-info">New</span>
											<span class="<?php if ($row->status != 'in progress') echo 'stats-not-active'?> stat-in-progess badge badge-secondary">In Progess</span>
											<span class="<?php if ($row->status != 'completed') echo 'stats-not-active'?> stat-completed badge badge-success">Completed</span>
										</div>
									</div>
									<div class="col-md-4 text-right">
										<span class="datebox">
											<i class="fas fa-calendar"></i>&nbsp;<?php echo $row->mydate?>
										</span>
									</div>
								</div>								
							</div>
						</div>
					</div>
					
	<?php endforeach;?>
<?php endif;?>