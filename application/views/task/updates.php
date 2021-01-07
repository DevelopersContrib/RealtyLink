<?php if ($this->session->userdata('page')=='contractor'):?>
  <?php $this->load->view('dashboard/header');?>
  <?php $this->load->view('dashboard/navigation');?>
  <?php else:?>
  <?php $this->load->view('project-owner/dashboard/header');?>
  <?php $this->load->view('project-owner/dashboard/navigation');?>

<?endif?>
<!-- include summernote css/js -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.10/summernote.min.js"></script>
<link rel="stylesheet" href="/assets/css/mytask.css">
<style>
.project-container {
    padding: 20px 5px 20px;
}
.note-popover {
	display:none;
}
</style>
<?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item"><a href="/tasks/my">My Tasks</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $task->row()->title?> Updates</li>
		</ol>
	</div>
  <?php else:?>
   <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page"><?php echo $task->row()->title?> Updates</li>
		</ol>
	</div>
<?endif?>  
   
<div class="page-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="explore-heading">
					<div class="row">
						<div class="col-md-8">
							<h3><a href="/tasks/details/<?php echo $task->row()->id?>/<?php echo url_title($task->row()->title, 'dash', true)?>"><?php echo $task->row()->title?></a></h3>
						</div>
						<div class="col-md-4 text-right">
						  <?php if ($this->session->userdata('page')=='contractor'):?>
						  
						  <?php if ($this->taskcontributionsdata->checkexist('task_id',$task->row()->id,'status','approved')):?>
						  <div class="alert alert-success" role="alert">
						  <small><b>
                         Successfully sent&nbsp;
                           <?php $query = $this->taskcontributionsdata->getbyattribute('task_id',$task->row()->id);?>
                           <?php if ($query->num_rows() > 0):?>
                           		<?php foreach ($query->result() as $rowt):?>
                           		<a href="<?php echo $this->config->item('etherscan_'.$rowt->network)?>tx/<?php echo $rowt->trans_id?>" target="_blank">
                           		
                           		<?php if ($rowt->token_currency == 'SCESH'):?>
                           		     	<span class="badge badge-pill badge-info"> <?php echo $rowt->token_amount?> <?php echo $this->config->item('servicechain_token')?></span>	
                           			<?php else:?>
                           			<span class="badge badge-pill badge-secondary"> <?php echo $rowt->token_amount?> USDC</span>
                           		<?php endif?>
                           		 </a> 
                           		<?php endforeach;?>
                           		&nbsp;for this task.
                           <?php endif?>
                          </b> </small>
                         </div>
                         
                         <?php else:?>
                             <div class="input-group">
								<select class="custom-select" id="task_status_update">
									<option value="new" <?php if ($task->row()->status=='new') echo 'selected'?>>New</option>
									<option value="onprocess" <?php if ($task->row()->status=='in progress') echo 'selected'?>>In Progress</option>
									<option value="forapproval" <?php if ($task->row()->status=='for approval') echo 'selected'?>>For Approval</option>
									<option value="completed" <?php if ($task->row()->status=='completed') echo 'selected'?>>Completed</option>
								</select>
								<div class="input-group-append">
								    <button class="btn btn-secondary btnUpdateStatus" type="button">Update</button>
								</div>
							</div>
                         <?php endif?>
						  
							
						  <?php endif?>	
						  
						  
						  <?php if ($this->session->userdata('page')=='homeowner'):?>
						  
						   <?php if ($this->taskcontributionsdata->checkexist('task_id',$task->row()->id,'status','approved')):?>
						  <div class="alert alert-success" role="alert">
						  <small><b>
                         Successfully sent&nbsp;
                           <?php $query = $this->taskcontributionsdata->getbyattribute('task_id',$task->row()->id);?>
                           <?php if ($query->num_rows() > 0):?>
                           		<?php foreach ($query->result() as $rowt):?>
                           		<a href="<?php echo $this->config->item('etherscan_'.$rowt->network)?>tx/<?php echo $rowt->trans_id?>" target="_blank">
                           		
                           		<?php if ($rowt->token_currency == 'SCESH'):?>
                           		     	<span class="badge badge-pill badge-info"> <?php echo $rowt->token_amount?> <?php echo $this->config->item('servicechain_token')?></span>	
                           			<?php else:?>
                           			<span class="badge badge-pill badge-secondary"> <?php echo $rowt->token_amount?> USDC</span>
                           		<?php endif?>
                           		 </a> 
                           		<?php endforeach;?>
                           		&nbsp;for this task.
                           <?php endif?>
                          </b> </small>
                         </div>
						   <?php endif?>
						  <?php endif?>
						</div>
					</div>
				</div>
				<div class="row project-container">
					<div class="col-md-12">
						<div class="task-update-box" id="task-update-box">
							
              
							
						</div>
					</div>
          <div class="col-md-12">
						<div class="task-update-box">
            	<div class="alert alert-danger" role="alert" id="updateError" style="display: none">
                    This is a danger alert—check it out!
              </div>

                            <?php if ($task->row()->status != 'completed'):?>     
							<div id="summernote"></div> 
							<div class="task-send mt-3">
								<button id="bntSaveUpdate" class="btn btn-secondary" type="button">Send</button>
							</div>
							<?php endif?>
						</div>
					</div>
				</div>
			</div>			
		</div>
	</div>
</div>

<input type="hidden" name="update_task_id" id="update_task_id" value="">
<script>

$(document).ready( function () {
	loadupdates();
    $("#bntSaveUpdate").on("click",function() {
        processUpdate();
    });

    $(".btnUpdateStatus").on("click",function() {
    	processStatus();
    });
    
  
});

  var task_id = '<?php echo $task->row()->id?>';
	$('#summernote').summernote({
	  tabsize: 2,
	  height: 200,
    callbacks: {
      onImageUpload: function(image) {
      editor = $(this);
      uploadImageContent(image[0], editor);
      }
     }
	});
  
  function uploadImageContent(image, editor) {
    var data = new FormData();
    data.append("image", image);
    $.ajax({
    url: "/task/uploadimageupdate",
    cache: false,
    contentType: false,
    processData: false,
    data: data,
    type: "post",
    success: function(url) {
    var image = $('<img>').attr('src', url);
    $(editor).summernote("insertNode", image[0]);
    },
    error: function(data) {
    console.log(data);
    }
    });
}

  function loadupdates(){
    $.ajax({
		method: "POST",
		url:  "/task/loadupdates",
		data: {task_id:task_id}
	})
	.success(function( data ) {
	     $('#task-update-box').html(data.html);
	});
  }
  
  
  function processEdit(id){
    var html = $('#message_content_'+id).html();
    $('#summernote').summernote('code',html);
    $('#update_task_id').val(id);
  
  }
  
  function processDelete(id){
     $.ajax({
  		method: "POST",
  		url:  "/task/deleteupdate",
  		data: {id:id}
  	})
  	.success(function( data ) {
  	     $('#update_'+data.id).fadeOut();
  	});
  
  }
  
  function processUpdate(){
	$('#updateError').hide();
	$('#updateError').html('');
    var message = $('#summernote').summernote('code');
    var update_id = $('#update_task_id').val();
    if (message == ""){
    	$('#updateError').html('Please enter message');
    	$('#updateError').show();
    }else {
        $('#bntSaveUpdate').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Processing').attr('disabled',true);

        $.post('/task/saveupdate',{
            task_id:task_id,
            message:message,
            update_id:update_id
        },function(data){
            if(data.status){
               loadupdates();
               $("#summernote").summernote("code", "");
               $('#bntSaveUpdate').html('Send').attr('disabled',false);
               $('#update_task_id').val('');
                }else{
            	$('#updateError').html(data.message);
            	$('#updateError').show();
            }
        });
    }
	
}

  function processStatus(){
		var status = $('#task_status_update').val();
		$('.btnUpdateStatus').html('Saving <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>').attr('disabled','disabled');
	    jQuery.post('/tasks/updatestatus',
					{
				      status:status,
				      id:task_id
				    },
					function(data){
				    	if (data.success){
				    		Swal.fire({
				    			type: 'success',
				    			title:'Success',
				    			text: 'You successfully updated task status',
				    			showConfirmButton: false,
				    			timer: 1500
				    		});
				    	}else {
				    		Swal.fire({
				    			  type: 'error',
				    			  title: 'Oops...',
				    			  text: data.error
				    			})
				    	}
				    	$('.btnUpdateStatus').html('Update').attr('disabled',false);
				});
	}
</script>
  
<?php $this->load->view('dashboard/footer');?>	