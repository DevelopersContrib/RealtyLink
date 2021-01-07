<?php if ($updates->num_rows() > 0): ?>
  <?php foreach ($updates->result() as $row): ?>
      <div class="task-post mb-4" id="update_<?php echo $row->id?>">
								<div class="media">
                <?php if ($row->profile_image == null):?>
									<img src="http://cdn.vnoc.com/servicechain/user-default.png" class="mr-2 rounded-circle task-user-image" alt="" width="40" height="40">
                  <?php else:?>
                  <img src="<?php echo $row->profile_image?>" class="mr-2 rounded-circle task-user-image" alt="" width="40" height="40">
              <?php endif?>
									<div class="media-body">
										<h6 class="mt-0 mb-0"><?php echo $row->firstname.' '.$row->lastname?></h6>
										<div class="timedate-posted"><small><?php echo date('M j Y', strtotime($row->date_created));?></small></div>
										<div class="task-d">
											<small id="message_content_<?php echo $row->id?>">
											<?php echo $row->message;?>
											</small>
										</div>
									</div>
									<div class="ml-3">
                    <?if ($this->session->userdata('userid')==$row->userid):?>
										<a href="javascript:void(0);" class="btnEditUpdate" id="<?php echo $row->id?>" title="Edit"><i class="fas fa-edit"></i></a>
										<a href="javascript:void(0);" class="btnDeleteUpdate" id="<?php echo $row->id?>" title="Delete"><i class="fas fa-trash"></i></a>
                    <?php endif?>
									</div>
								</div>
							</div>
   <?php endforeach?>   
   <?php else:?>  
   <div class="alert alert-warning" role="alert">
  No updates for this task yet.
</div>      
<?php endif?>

<script>
$(document).ready( function () {
	 $('.btnDeleteUpdate').on("click",function() {
   	var id = $(this).attr('id');
    bootbox.confirm("Are you sure you want to delete this update?", function(result){
	        
            processDelete(id);
	    	}) 
		return false;
        
    }); 
    
    $('.btnEditUpdate').on("click",function() {
        var id = $(this).attr('id');
        processEdit(id);
    }); 
});
</script>