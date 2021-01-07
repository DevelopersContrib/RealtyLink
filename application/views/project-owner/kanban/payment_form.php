<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Payment Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >
			    <?php if (($task->row()->completed_payment_details!=null) && ($task->row()->completed_payment_details!='')): ?>
			   <div class="row">
					<div class="col-lg-12">
						<div class="bg-info p-3 text-white">
							<h5>
								Payment:
							</h5>
							<p id="payment_details_content">
							  <?php echo $task->row()->completed_payment_details?>
							</p>
						</div>
					</div>
					<div class="col-lg-12">
						<hr>
					</div>
					<div class="col-lg-6 mb-5">
						<label for="">
							Status
						</label>
						<select name="payment_status" id="payment_status" class="form-control">
							<option value="pending" <?php if ($task->row()->completed_payment_status=='pending') echo 'selected'?>>pending</option>
							<option value="on process" <?php if ($task->row()->completed_payment_status=='on process') echo 'selected'?>>on process</option>
							<option value="sent" <?php if ($task->row()->completed_payment_status=='sent') echo 'selected'?>>sent</option>
						</select>
					</div>
				</div>
				<?php else:?>
				<div class="alert alert-info" role="alert">
  						Service provider did not send any payment details yet!</div>
				<?php endif?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
				<?php if (($task->row()->completed_payment_details!=null) && ($task->row()->completed_payment_details!='')): ?>
				<button type="button" class="btn btn-primary" id="btnSavePayStatus" onclick="savepaymentstatus(<?php echo $task->row()->id?>);"><i class="fas fa-check"></i> Submit</button>
				<?php endif?>
			</div>