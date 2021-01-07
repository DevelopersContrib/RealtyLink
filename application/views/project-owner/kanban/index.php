<?php if ($this->session->userdata('logged_in')):?>
    <?php $this->load->view('project-owner/dashboard/header');?>
    <?php $this->load->view('project-owner/dashboard/navigation');?>
    
<link rel="stylesheet" href="/assets/css/mytask.css">
<link rel="stylesheet" href="/assets/css/loading.css">
<link rel="stylesheet" href="/assets/css/star.css">
<style>

</style>
<link href="/assets/js/vendor/Trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">	
<script src="/assets/js/vendor/Trumbowyg/trumbowyg.min.js"></script>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Kanban</li>
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
		  url:"/project-owner/kanban/loadpayment",
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
	 var status = $('#payment_status').val();
	$.ajax({
		  url:"/project-owner/kanban/savepaymentstatus",
		  method:"POST",
		  data:{id:id,status:status},
		  cache:false,
		  success:function(data)
		  {
		    $('#modal-form-content').html('');
			$('#paymentmodalform').modal('hide');
			 $('#btnSavePayStatus').html('<i class="fas fa-check"></i> Submit').attr('disabled',false);
			 Swal.fire({
				  type: 'success',
				  title: 'Success',
				  text: 'You successfully updated payment status.',
				})
			 setTimeout(function(){ 
					window.location = '/project-owner/kanban'; 
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
					<h3>Kanban</h3> 
				</div>					
				<div class="row project-container">
					<!-- diri ibutang ang tab bai -->
					<div class="col-md-12">
						<div class="row s-gutters">
							<div class="col-md-12">
								<div class="form-box mb-2 mt-3">					
									<form class="form">
										<div class="form-row">
											<div class="form-group col-md-9">
												<select class="form-control" id="filter_project">
													<option value=""></option>
												   <?php if ($qprojects->num_rows() > 0): ?>
														<?php foreach ($qprojects->result() as $row): ?>
														  <option value="<?php echo $row->id?>"><?php echo $row->title?></option>
														<?php endforeach;?>
												   <?php endif?>
												</select>
											</div>
																
											<div class="form-group col-md-3">
												<a href="javascript:void(0)" class="btn btn-block btn-secondary btn-ser" id="BtnSearchProj">Search by Project</a>
											</div>
										</div>
									</form>
								</div>	
							</div>
						</div>
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
							  <h6 class="alert alert-success"><i class="fas fa-check"></i>&nbsp;Completed</small></h6>
							  <ol data-draggable="target" class="box" id="completed">
								
							</ol>
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
<style>
.loading-task {
	position: absolute;
	height: 100%;
	width: 100%;
	background: rgba(0,0,0,.85);
	padding: 260px 300px 100px;
	text-align: center;
	color:	#fff;
	z-index: 999;
}
.loading-task-inner .desc-highlight {
	color: #F2C087;
	font-weight: 800;
	font-size: 1.5rem;
}
.loading-task-inner .desc-highlight-2 {
	color: #F2C087;
}
.container-animate {
	margin: 100px auto 30px;
	width: 500px;
	text-align: center;
}
.progress {
	padding: 6px;
	background: rgba(0, 0, 0, 0.25);
	border-radius: 6px;
	box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.25), 0 1px rgba(255, 255, 255, 0.08);
	height: 2rem;
}
.progress-bar {	
	height: 20px;
	background-color: #ee303c;  
	border-radius: 4px; 
	transition: 0.4s linear;  
	transition-property: width, background-color;    
}
.progress-striped .progress-bar { 	
	background-color: #FCBC51; 
	width: 100%; 
	background-image: linear-gradient(
	45deg, rgb(252,163,17) 25%, 
	transparent 25%, transparent 50%, 
	rgb(252,163,17) 50%, rgb(252,163,17) 75%,
	transparent 75%, transparent); 
	animation: progressAnimationStrike 6s;
}
@keyframes progressAnimationStrike {
     from { width: 0 }
     to   { width: 100% }
}
</style>
<div class="loading-task" style="display:none;">
	<div class="loading-task-icon">
		<div class="container-animate">    
			<div class="progress progress-striped">
				<div class="progress-bar"></div>                       
			</div> 
		</div>
	</div>
	<div class="loading-task-inner">
		
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="paymentmodalform" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content" id="modal-form-content">
			
		</div>
	</div>
</div>
<script>
$( document ).ready(function() {
	$(document).on("click",".star.rating",function(e) {
        let starEl = e.currentTarget;
		console.log(starEl.parentNode.dataset.stars + ", " + starEl.dataset.rating);
		starEl.parentNode.setAttribute('data-stars', starEl.dataset.rating);
    });
	$(document).on("click","#BtnSearchProj",function() {
		loadmytasks('new');
		loadmytasks('onprocess');
		loadmytasks('forapproval');
		loadmytasks('completed');
	});

	
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
             if (box == 'completed'){
                 var has_e = false;

                 var t_payment = $('#taskspayment'+id).val();
                 
                 if (t_payment == 'cash'){
                	  var t_details = $('#taskspaymentdetails'+id).val();
                      var t_status = $('#taskspaymentstatus'+id).val();
                      if (t_details == ''){
                    	  $('#payment_status').hide();
                      }else {
                    	 $('#payment_status').attr('disabled',false);
                      	$('#payment_status').val(t_status);
                      }
                      $('#payment_details_content').html('<small>'+t_details+'</small>');
                      if ((t_status == 'pending') || (t_status == 'on process')) {
                    	  has_e = true;
                    	  Swal.fire({
    						  type: 'error',
    						  title: 'Oops...',
    						  text: 'You need to send USDC payment first to service provider before completing this task. Payment details can be found on kanban task settings.',
    						})
    	   
    						setTimeout(function(){ 
    							window.location = '/project-owner/kanban'; 
    							}, 5000
    						);
  						return false;
                      }
                 }    


                 
                     
                  var html = $('#loadmessage'+id).html();
                  $('.loading-task-inner').html(html);
                  $('.loading-task').show();

                  setTimeout(
                          function(){ 
                        	  $.ajax({
                        			url: '/project-owner/kanban/updatestatus',
                        			type: 'POST',
                        			data: {
                        				'id' : id,
                        				'status' : box
                        			},
                        			'success': function(data) {
                        				$('.loading').hide();
                        				$('.loading-task').hide();
                        				if (data.success){
                        					$(ui.draggable).remove();
                        					$('#' + box).append('<li data-draggable="item" id="' + id + '">' + toy + '</div>');
                        					$('li#' + id).draggable({
                        						helper: 'clone'
                        					});
                        					$(this).css('min-height' , 'auto');

                        					if (data.success_message != ''){
                        						Swal.fire({
                        							  type: 'success',
                        							  title: 'Success',
                        							  html: data.success_message,
                        							
                        							}).then(result => {
														if (result.value) {
															$.ajax({
																url: '/project-owner/kanban/rate',
																type: 'POST',
																data: {
																	'project' : $('#rating').attr('data-project'),
																	'task' : $('#rating').attr('data-task'),
																	'rate' : $('#rating').attr('data-stars'),
																	'comment' : $('#comment').val(),
																},
																'success': function(data) {
																}
															});
														} else {
															
														}
													});
                        					}
                        				}else {
                        					Swal.fire({
                        						  type: 'error',
                        						  title: 'Oops...',
                        						  text: data.error,
                        						})
                        	   
                        						setTimeout(function(){ 
                        							window.location = '/project-owner/kanban'; 
                        							}, 3000
                        						);
                        				}
                        			}
                        		});
                              }, 8000);
                  
             }else {                 
             	$('.loading').show();
             	$.ajax({
            		url: '/project-owner/kanban/updatestatus',
            		type: 'POST',
            		data: {
            			'id' : id,
            			'status' : box
            		},
            		'success': function(data) {
            			$('.loading').hide();
            			$('.loading-task').hide();
            			if (data.success){
            				$(ui.draggable).remove();
            				$('#' + box).append('<li data-draggable="item" id="' + id + '">' + toy + '</div>');
            				$('li#' + id).draggable({
            					helper: 'clone'
            				});
            				$(this).css('min-height' , 'auto');

            				if (data.success_message != ''){
            					Swal.fire({
            						  type: 'success',
            						  title: 'Success',
            						  html: data.success_message,
            						
            						})
            				}
            			}else {
            				Swal.fire({
            					  type: 'error',
            					  title: 'Oops...',
            					  text: data.error,
            					})
               
            					setTimeout(function(){ 
            						window.location = '/project-owner/kanban'; 
            						}, 3000
            					);
            			}
            		}
            	});
             }

             
		}
	});
	
});




function loadmytasks(status){
	var project_id = $('#filter_project').val();
	$.ajax({
		method: "POST",
		url:  "/project-owner/kanban/loadmytasks",
		data: {status:status,project_id:project_id  }
	})
	.success(function( data ) {
		$('ol#'+data.status).html(data.html);
		$('#'+data.status+' li').draggable({
			helper: 'clone'		
		});
	});
}
</script>

<?php $this->load->view('dashboard/footer');?>	