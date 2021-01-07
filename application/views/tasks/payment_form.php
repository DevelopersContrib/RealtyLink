<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Would you like to request for cash payment?</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >
			   <?php if ($task->row()->completed_payment_status =='pending'): ?>
			   <div class="row">
					<div class="col-lg-12">
						<div class="bg-info p-3 text-white">
							<h5>
								Status:
							</h5>
							<p id="payment_status_content">
							  <?php echo $task->row()->completed_payment_status?>
							</p>
						</div>
					</div>
					<div class="col-lg-12">
						<hr>
					</div>
					<div class="col-lg-6 mb-5">
						<label for="">
							Payment Details
						</label>
						<textarea class="form-control" id="sp_payment_details" rows="3"><?php echo $task->row()->completed_payment_details?></textarea>
					</div>
				</div>
				<?php else:?>
				<div class="alert alert-info" role="alert">
  						You can no longer update payment details for on process or sent payments
  				</div>
  				<div class="row">
					<div class="col-lg-12">
						<div class="bg-info p-3 text-white">
							<h5>
								Status:
							</h5>
							<p id="payment_status_content">
							  <?php echo $task->row()->completed_payment_status?>
							</p>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="bg-info p-3 text-white">
							<h5>
								Details:
							</h5>
							<p id="payment_status_content">
							  <?php echo $task->row()->completed_payment_details?>
							</p>
						</div>
					</div>
				</div>
  				
				<?php endif?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
			   <?php if ($task->row()->completed_payment_status =='pending'): ?>
				<button type="button" class="btn btn-primary" id="btnSavePayStatus" onclick="savepaymentstatus(<?php echo $task->row()->id?>);"><i class="fas fa-check"></i> Submit</button>
				<?php endif?>
			</div>