<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>

	<!-- <div class="page-container" style="min-height: 548px;"> -->
	<div class="page-container">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="project-heading">
						<h3>Your Projects</h3> 
					</div>					
				</div>
				<div class="col-md-12">
					<div class="project-container">
						<div class="row">
							<div class="col-md-4">
								<img class="img-fluid" src="http://cdn.vnoc.com/logos/logo-ServiceChain-1.png">
							</div>
							<div class="col-md-6">
							
							</div>
							<div class="col-md-2">
								<a href="" class="btn btn-block btn-sm btn-warning">Edit</a>
								<a href="" class="btn btn-block btn-sm btn-danger">Delete</a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="col-md-4">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
							<div class="col-md-4">
								<ul class="list-group">
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
									<li class="list-group-item">
										<div class="row">
											<div class="col-md-6">
												<div class="row-desc">
													esh value
												</div>
											</div>
											<div class="col-md-6">
												<div class="row-value">
													0.59
												</div>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
	<!-- Add Project Modal-->
	<div class="modal" tabindex="-1" role="dialog" id="addproModal">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Project</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body">
					<form class="addprojectform">
						<div class="form-group">
							<label>Project Title</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>Project Description</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>States</label>
							<select class="form-control" id="">
								<option>...</option>
							</select>
						</div>
						<div class="form-group">
							<label>City</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>Address</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>Zipcode</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>Status</label>
							<input type="text" class="form-control" id=""> 
						</div>
						<div class="form-group">
							<label>Gallery ( Upload Multiple )</label>
							<input type="file" class="form-control-file" id="">
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-primary float-right">Submit</button>
							<div class="clearfix"></div>
						</div>						
					</form>
				</div>				
			</div>
		</div>
	</div>

<?php $this->load->view('project-owner/dashboard/footer');?>
	
	