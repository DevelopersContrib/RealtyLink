<style>
.protitle {
	font-weight: 800;
	color: #666;
}
.statsbox .badge-primary {
	background: #F9B971;
}
.asstats .badge {
	padding: .3em .5em;
	width: auto;
	font-size: 80%;
}
.asstats .stats-not-active {
	opacity: .15;
}
.col-bdr {
	border-top: 1px solid #E3E4E8;
}
.btnc {
	color: #ffffff !important;
}
.asstats .badge-info {
    color: #fff;
    background-color: #C69C6D;
}
</style>
<?php  if ($query->num_rows() > 0):?>
	<?php foreach ($query->result() as $row):?>
		<div class="col-md-12" id="project-content-div<?php echo $row->id?>">
					<!-- project-container -->
					<div class="project-container mb-3" id="<?php echo $row->id?>" data="<?php echo $row->slug?>" style="cursor:pointer;">
						<div class="row mb-2">
							<div class="col-md-3">
							   <?php if (($row->icon_image !=null) && ($row->icon_image !='')):?>
							   <img class="img-fluid" src="<?php echo $row->icon_image?>" width="120">
							     <?php else:?>
								<img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-servicechain.png" width="120">
							  <?php endif?>	
							</div>
							<div class="col-md-6 text-center">
								<h4 class="protitle">
									<?php echo $row->title?>
								</h4>
								<!-- <small><span class="badge badge-danger col-md-3"><?php echo $row->status?></span></small> -->
								<?php if ($this->projectcontractdata->hascontract($row->id,'data')===false):?>
							    	<a class="btn btn-sm btn-info btnc btncreatedata<?php echo $row->id?>"  id="<?php echo $row->id?>"  data="<?php echo $row->title?>" title="Create data contract">Create data contract</a>
							        <?php else:?>
							        <?php if ($this->projectcontractdata->hascontract($row->id,'dan')===false):?>
							        <a class="btn btn-sm btn-info btnc btncreatedan<?php echo $row->id?>"  id="<?php echo $row->id?>"  data="<?php echo $row->title?>" title="Create dan contract">Create dan contract</a>
							        <?php endif?>
							        
							    <?php endif?>  
							</div>							
							<div class="col-md-3 mt-2 text-right">							    
								<a class="btn btn-sm btn-outline-secondary btnstatus<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="change status"><i class="fas fa-cog"></i></a>
								<a class="btn btn-sm btn-outline-warning btnedit<?php echo $row->id?>"  id="<?php echo $row->id?>" title="edit"><i class="fas fa-edit"></i></a>
								<a class="btn btn-sm btn-outline-danger btndelete<?php echo $row->id?>"  id="<?php echo $row->id?>"  title="delete"><i class="fas fa-trash"></i></a>
							</div>
						</div>
						<div class="row no-gutters">
						   <?php if ($this->projectcontractdata->hascontract($row->id,'dan')):?>
							<div class="col-md-12">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row align-self-center">
											<div class="col-md-8 convalue">
												<b>Contract:</b><br>
												<?php
													$network = $this->projectcontractdata->getinfo('network','project_id',$row->id,'contract_type','dan');
													$hash = $this->projectcontractdata->getinfo('trans_id','project_id',$row->id,'contract_type','dan');
													$link = 'https://etherscan.io/address/';
													$txlink = 'https://etherscan.io/tx/';
													if($network == 'test'){
														$link = 'https://ropsten.etherscan.io/address/';
														$txlink = 'https://ropsten.etherscan.io/tx/';
													}
													$address = $this->projectcontractdata->getinfo('address','project_id',$row->id,'contract_type','dan');
												?>
												<span class="convalues"><a class="vendor vendor<?=$row->id?>" target="_blank" href="<?=$link.$address?>"><?php echo $address?></a></span>
												<?php
													if(!empty($hash)){
												?>
												<br><b>Transaction:</b><br>
												<span class="convalues"><a class="vendor vendor<?=$row->id?>" target="_blank" href="<?=$txlink.$hash?>"><?php echo $hash?></a></span>
												<?php
													}
												?>
											</div>
											<div class="col-md-4 align-self-center">
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
											<span class="badge badge-primary">
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
											<span class="badge badge-primary">
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
											<span class="badge badge-primary">
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
					<!-- project-container -->
				</div>
				
<script>     
$(document).ready(function(){
	$(document).on("click",".btnedit<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        loadform(id);       
        return false;
    });
	$(document).on("click",".btnstatus<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        loadchangestatus(id);     
        return false;
    });

	$(document).on("click",".btndelete<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        // bootbox.confirm("Are you sure?", function(result){
        	// deleteproject(id);
    	// })
		Swal.fire({
			title: 'Delete',
			icon: 'info',
			html:
			'Are you sure you want to delete project?',
			showCloseButton: true,
			showCancelButton: true,
			focusConfirm: false,
			confirmButtonText:
			'Yes',
			confirmButtonAriaLabel: 'Thumbs up, great!',
			cancelButtonText:
			'No',
			cancelButtonAriaLabel: 'Thumbs down'
		}).then(result => {
			if (result.value) {
				deleteproject(id);
			}
		});
		
        return false;
    });

	$(document).on("click",".btncreatedata<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        loadloader(id,'Generating data contract...');
        processcontractdata(id);
        return false;
    });

	$(document).on("click",".btncreatedan<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        loadloader(id,'Generating dan contract...');
        processcontractdan(id);
        return false;
    });

	$(document).on("click",".btnmintesh<?php echo $row->id?>",function() {
        var id= $(this).attr('id');
        loadloader(id,'Minting 1,000,000 SCESH to dan contract');
        processeshtransfer(id);
        return false;
    });

	
});
</script>
	<?php endforeach;?>
<?php endif;?>