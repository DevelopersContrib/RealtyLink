<?php if ($this->session->userdata('logged_in')):?>
    <?php $this->load->view('dashboard/header');?>
    <?php $this->load->view('dashboard/navigation');?>
    
<link rel="stylesheet" href="/assets/css/mytask.css">
<link rel="stylesheet" href="/assets/css/loading.css">
<style>

</style>
<link href="/assets/js/vendor/Trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">	
<script src="/assets/js/vendor/Trumbowyg/trumbowyg.min.js"></script>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">My Tasks</li>
		</ol>
	</div>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>


<script>
function setpdetails(id){
	$('.loading').show();
	$.ajax({
		  url:"/tasks/loadpayment",
		  method:"POST",
		  data:{id:id},
		  cache:false,
		  success:function(data)
		  {
		   $('#modal-form-content').html(data.html);
			$('#paymentmodalform').modal('show');
			$('.loading').hide();
		  }
		 });
}

function gettransaction(id){
	$('.loading').show();
	$.ajax({
		  url:"/project-owner/kanban/loadtransactions",
		  method:"POST",
		  data:{id:id},
		  cache:false,
		  success:function(data)
		  {
		   $('#modal-form-content').html(data.html);
			$('#paymentmodalform').modal('show');
			$('.loading').hide();
		  }
		 });
}

function savepaymentstatus(id){
	 $('#btnSavePayStatus').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Saving').attr('disabled',true);
	 var details = $('#sp_payment_details').val();
	$.ajax({
		  url:"/tasks/savepaymentstatus",
		  method:"POST",
		  data:{id:id,details:details},
		  cache:false,
		  success:function(data)
		  {
		    $('#modal-form-content').html('');
			$('#paymentmodalform').modal('hide');
			 $('#btnSavePayStatus').html('<i class="fas fa-check"></i> Submit').attr('disabled',false);
			 Swal.fire({
				  type: 'success',
				  title: 'Success',
				  text: 'You successfully updated payment details.',
				})
			 setTimeout(function(){ 
					window.location = '/tasks/my'; 
					}, 4000
				);
		  }
		 });
}
</script>
<div class="page-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="explore-heading">
					<h3>My Tasks</h3> 
				</div>					
				<div class="row project-container">
					<!-- diri ibutang ang tab bai -->
					<div class="col-md-12">
						<div class="tab-container">
							<ul class="nav nav-tabs nav-justified" id="taskTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="latest-tab" data-toggle="tab" href="#task-my"><i class="far fa-list-alt"></i>&nbsp;My Tasks <span class="count"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="withapp-tab" data-toggle="tab" href="#task-applications" role="tab"><i class="far fa-file-alt"></i>&nbsp;My Applications (<span class="count" id="app_task_count"></span>)</a>
								</li>
								
							</ul>
							  <div class="tab-content" id="taskTabContent">
								<div class="tab-pane fade show active" id="task-my" role="tabpanel">
								  <div class="row draggable-task-container s-gutters">
									     <div class="col-md-3">
										  <h6 class="alert alert-secondary"><i class="fas fa-archive"></i>&nbsp;New</h6>
										  
										  <ol data-draggable="target" class="box" id="new">
																				
										    </ol>
									     </div>								    
									     <div class="col-md-3">
										  <h6 class="alert alert-info"><i class="fas fa-file"></i>&nbsp;In Progress</h6>
										  <ol data-draggable="target" class="box" id="onprocess">
											
									    </ol>    
									     </div>
									     <div class="col-md-3">
										  <h6 class="alert alert-danger"><i class="fas fa-thumbs-up"></i>&nbsp;For Approval</h6>
											<ol data-draggable="target" class="box" id="forapproval">
												
										    </ol>								        
									     </div>
									     <div class="col-md-3">
										  <h6 class="alert alert-success"><i class="fas fa-check"></i>&nbsp;Completed&nbsp;<small>(task owners only)</small></h6>
										  <ol data-draggable="target" class="boxko" id="completed">
											
										</ol>
									     </div>
								  </div>
								</div>
								<div class="tab-pane fade" id="task-applications" role="tabpanel">
									<div class="form-box ml-2 mr-2 mb-2 mt-3">					
										<form class="form">
											<div class="form-row">
												<div class="form-group col-md-9">
													<input type="text" class="form-control " id="search_key" placeholder="">
												</div>
																	
												<div class="form-group col-md-3">
													<a href="javascript:loadmyapplications(1)" class="btn btn-block btn-secondary btn-ser ">Search</a>
												</div>
											</div>
										</form>
									</div>
									<div class="task-loader-loading" style="display: none">
										<div class="text-center">
									    <div class="spinner-border" role="status">
										<span class="sr-only">Loading...</span>
									    </div>
									  </div>
								     </div>			
									<div  class="row" id="taskcontainer">
									</div>
								</div>								
							</div>
						</div>
					</div>
					<!-- -->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="loading" style="display:none">Loading&#8230;</div>
<!-- Modal -->
<div class="modal fade" id="paymentmodalform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content" id="modal-form-content">
			
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
	loadmyapplications(1);
	loadmytasks('new');
	loadmytasks('onprocess');
	loadmytasks('forapproval');
	loadmytasks('completed');
    var a = 1;
	$('.box li').draggable({
		helper: 'clone',
    start: function(event, ui) { $(this).css("z-index", a++); }		
	});
	$('.box').droppable({
		tolerance: 'touch',
    accept: '.box li', 
		drop: function(event,ui) {
			var id = $(ui.draggable).attr('id');
			var toy = $(ui.draggable).html();
			var box = $(this).attr('id');
           $('.box li#' + id).remove();
            $('.loading').show();
            
            $.ajax({
				url: '/tasks/updatestatus',
				type: 'POST',
				data: {
					'id' : id,
					'status' : box
				},
				'success': function(data) {
					$('.loading').hide();
					if (data.success){
						$(ui.draggable).remove();
    					$('#' + box).append('<li data-draggable="item" id="' + id + '">' + toy + '</div>');
    					$('li#' + id).draggable({
    						helper: 'clone'
    					});
    					$(this).css('min-height' , 'auto');
    					if (box  == 'forapproval'){
							setpdetails(data.taskId);
    					}
					}else {
						Swal.fire({
							  type: 'error',
							  title: 'Oops...',
							  text: data.error,
							})
           
							setTimeout(function(){ 
								window.location = '/tasks/my'; 
								}, 3000
							);
					}
				}
			});
			
		}
	});
	
});



function loadmyapplications(page = '1',from=''){
    var search_key = $('#search_key').val();
    var sort_by = $('#search_sort').val();
		
			$('.task-loader-loading').show();
			$('#taskcontainer').html(' ');

			$.ajax({
				method: "POST",
				url:  "/tasks/loadapplications",
				data: { 'page':page,'search_key':search_key,'sort_by':sort_by,'from':from }
			})
			.success(function( data ) {
				$('.task-loader-loading').hide();
				$('#taskcontainer').html(data.html);
				$('#app_task_count').html(data.count);
				
			});
		}


function loadmytasks(status){
	$.ajax({
		method: "POST",
		url:  "/tasks/loadmytasks",
		data: {status:status  }
	})
	.success(function( data ) {
		$('ol#'+data.status).html(data.html);
		if (data.status != 'completed'){
    		$('#'+data.status+' li').draggable({
    			helper: 'clone'		
    		});
		}
	});
}
</script>

<?php $this->load->view('dashboard/footer');?>	