<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Blockchain Transaction</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >
			 
			 
			 
			<div class="col-md-12 table-outer">
				<div class="table-box">
					<!-- -->
					<table id="kantranstable" class="table table-striped table-bordered nowrap" style="width:100%">
						<thead>
							<tr>
								
								<th>Transaction ID</th>
								<th>Amount</th>
								<th>Currency</th>
								<th>Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
						 <?php if ($trans->num_rows() > 0):?>
						 	<?php foreach ($trans->result() as $rowt):?>
        						 <tr>
        						    <td>
        						    	<a href="<?php echo $this->config->item('etherscan_'.$rowt->network)?>tx/<?php echo $rowt->trans_id?>" target="_blank"><?php echo $rowt->trans_id?></a>
        						    </td>
        						    <td>
        						    <?php echo $rowt->token_amount?>
        						    </td>
        						    <td>
        						    <?php echo $rowt->token_currency?> 
        						    </td>
        						     <td>
        						    <?php echo $rowt->date_of_transaction?> 
        						    </td>
        						    <td>
        						    <?php echo $rowt->status?> 
        						    </td>
        						 </tr>
						   <?php endforeach;?>
						 <?php endif?>
																				
						</tbody>
						
					</table>
					<!-- -->
				</div>
			</div>
			   	
    			   	
                          
              
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
				
			</div>