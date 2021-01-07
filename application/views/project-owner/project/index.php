<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<link rel="stylesheet" href="/assets/css/loading.css">
<style>
.project-container {
	background: #fff;
	border: 1px solid #eaeaea !important;
	padding: 20px 5px 5px;
	border: none;
}
.project-container:hover {
	box-shadow: none;
	transform: none;
}
.project-container .badge {
	width: auto;
	padding: .25em .5em;
	font-size: 85%;
}
.title:not(:last-child) {
    margin-bottom: 1.8rem;
}
.assto, .tasdate {
	font-size: 12px;
}
.card-content {
    padding: 1.5rem 1.0rem 1.5rem;
}
.project-content-height {
	max-height: 100%;
	min-height: auto;
}
.loader-box {
	margin-top: 30px;
}
.loader-box .fa-cog {
	font-size: 3.5rem;
	color: #999;
}
.task-heading h4 {
	font-weight: 600;
}
.task-box {
	padding: 0px;
}
.card {
    background-color: #fafafa;
    border: none;
}
.badge-light {
    color: #212529;
    background: #d1e5fd;
}
.badge-info {
	background: #C69C6D;
}
.nav-tabs .nav-link {
	font-weight: 600;
	padding: .6rem 1rem;
}
.nav-tabs .nav-link .far {
	font-size: 1.3rem;
	vertical-align: -3px;
	background: #f2f2f2;
	padding: 7px;
	border-radius: 50%;
	width: 36px;
	height: 36px;
	box-shadow: 0px 0px 2px #999;
}
.apptask-outer {
	background-color: #f2f2f2;
}
.apptask-outer .btn-link {
	color: #8C6239;
}
.apptask-outer .btn-link:hover {
	text-decoration: none;
	color: #C69C6D;
}
.apptask-outer .btn-link:focus {
	text-decoration: none;
}
.taskuser {
	font-weight: 600;
}
.apptasklist {
	background: #ffffff;
	border: 1px solid #eaeaea;
	border-radius: 3px;
	padding: 10px;
	margin-bottom: 10px;
}
.apptasklist .fas, .apptasklist .far {
	color: #888;
}
.accordion > .card .card-header {
	margin-bottom: 5px;
	border: none;
	padding: .15rem 1.0rem .35rem;
}
</style>
<link rel="stylesheet" href="/assets/css/star.css">
<link href="/assets/js/vendor/Trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">	
<script src="/assets/js/vendor/Trumbowyg/trumbowyg.min.js"></script>

<div class="breadcrumb-outer">
	<ol class="container breadcrumb">
		<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/project-owner/myprojects">My Projects</a></li>
		<li class="breadcrumb-item active"><?=ucwords($project['title'])?></li>
	</ol>
</div>

<div class="page-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="explore-heading">
					<h3><?=stripslashes($project['title'])?></h3> 
				</div>					
				<div class="row project-container">
					
					<div id="load_data_message" style="" class="col-md-12 text-center loader-box">
						<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>
					</div>
					<div class="col-md-7">
						<div class="task-heading">
						
							<p><?=stripslashes($project['description'])?></p>
						</div>						
					</div>	
					<div class="col-md-5 text-right mb-3">
						<a href="javascript:;" data-toggle="modal" data-target="#addproTask" class="btn btn-secondary">
							<i class="fas fa-plus" aria-hidden="true"></i> Add Task
						</a>
						<a href="/project/details/<?=$project['id']?>/<?=$project['slug']?>" target="_blank" class="btn btn-warning">
							Public Page
						</a>
						<a href="/project-owner/project/transactions/<?=$project['id']?>" target="_blank" class="btn btn-primary">
							Transactions
						</a>
					</div>
					
					<!-- diri ibutang ang tab bai -->
					<div class="col-md-12">
						<div class="tab-container">
							<?php
								$default_tab_link = 'active';
								$default_tab = 'show active';
								if(!empty($tab)){
									$default_tab_link = '';
									$default_tab = '';
								}
							?>
							<ul class="nav nav-tabs nav-justified" id="taskTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link <?=$default_tab_link?> <?=$tab=='latest'?'active':''?>" id="latest-tab" data-toggle="tab" href="#latest"><i class="far fa-list-alt"></i>&nbsp;Latest <span class="count"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link <?=$tab=='withapp'?'active':''?>" id="withapp-tab" data-toggle="tab" href="#withapp" role="tab"><i class="far fa-file-alt"></i>&nbsp;With Applications <span class="count"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link <?=$tab=='forapp'?'active':''?>" id="forapp-tab" data-toggle="tab" href="#forapp" role="tab"><i class="far fa-thumbs-up"></i>&nbsp;For Approval <span class="count"></span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link <?=$tab=='completed'?'active':''?>" id="completed-tab" data-toggle="tab" href="#completed" role="tab"><i class="far fa-check-square"></i>&nbsp;Completed <span class="count"></span></a>
								</li>
							</ul>
							<div class="tab-content" id="taskTabContent">
								<div class="tab-pane fade <?=$default_tab?> <?=$tab=='latest'?'show active':''?>" id="latest" role="tabpanel">
									<!-- -->
									<div class="row pt-4 pb-2">
										<div class="col-md-6">
											<div class="input-group mb-3">
												<input id="searchkey1" type="text" class="form-control searchkey" placeholder="">
												<div class="input-group-append">
													<button class="btn btn-secondary btnsearch" type="button" id="btnsearch1">Search</button>
												</div>
											</div>
										</div>
										<div class="col-md-6 d-none">
											<div class="form-group">
												<select class="form-control" id="sel-status">
													<option value="">Filter Category</option>
													<option SELECTED value="latest">Latest</option>
													<option value="with-applications">With Applications</option>
													<option value="pending">Pending</option>
													<option value="for-approval">For Approval</option>
													<option value="with-updates">With Updates</option>
												</select>
											</div>
										</div>
									</div>
									<div class="col-md-12">
										<nav class="page-next pagination-container">
											  
										</nav>
									</div>
									<!-- -->
								</div>
								<div class="tab-pane fade <?=$tab=='withapp'?'show active':''?>" id="withapp" role="tabpanel">
									<!-- -->
									<!--<div class="row pt-4 pb-2"></div>-->
									<div class="row pt-4 pb-2" style="">
										<div class="col-md-6">
											<div class="input-group mb-3">
												<input id="searchkey2" type="text" class="form-control searchkey" placeholder="">
												<div class="input-group-append">
													<button class="btn btn-secondary btnsearch" type="button" id="btnsearch2">Search</button>
												</div>
											</div>
										</div>										
									</div>
									
									<div class="col-md-12">
										<nav class="page-next pagination-container">
											  
										</nav>
									</div>
									<!-- -->
								</div>
								<div class="tab-pane fade <?=$tab=='forapp'?'show active':''?>" id="forapp" role="tabpanel">
									<!-- -->
									<div class="row pt-4 pb-2">
										<div class="col-md-6">
											<div class="input-group mb-3">
												<input id="searchkey3" type="text" class="form-control searchkey" placeholder="">
												<div class="input-group-append">
													<button class="btn btn-secondary btnsearch" type="button" id="btnsearch3">Search</button>
												</div>
											</div>
										</div>										
									</div>									
									<div class="col-md-12">
										<nav class="page-next pagination-container">
											  
										</nav>
									</div>
									<!-- -->
								</div>
								<div class="tab-pane fade <?=$tab=='completed'?'show active':''?>" id="completed" role="tabpanel">
									<!-- -->
									<div class="row pt-4 pb-2">
										<div class="col-md-6">
											<div class="input-group mb-3">
												<input id="searchkey4" type="text" class="form-control searchkey" placeholder="">
												<div class="input-group-append">
													<button class="btn btn-secondary btnsearch" type="button" id="btnsearch4">Search</button>
												</div>
											</div>
										</div>										
									</div>
									<div class="col-md-12">
										<nav class="page-next pagination-container">
											  
										</nav>
									</div>
									<!-- -->
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


<div class="modal fade bd-modal-delete" tabindex="-1" role="dialog" aria-labelledby="" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id=""></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <center><h6>Are you sure you want to delete?</h6></center>
      </div>
	  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button id="btn-delete" type="button" class="btn btn-danger">Yes</button>
      </div>
    </div>
  </div>
</div>
<!-- Add Task Modal-->
<div class="modal fade" tabindex="-1" role="dialog" id="addproTask">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Task</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div>
			<div class="modal-body">
				<?php $this->load->view('project-owner/task/task_form')?>
			</div>				
		</div>
	</div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="viewMessage">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div>
			<div class="modal-body">
				<form id="addtaskform">
					<div class="form-group">
						<span id="message-content"></span>
					</div>
				</form>
			</div>				
		</div>
	</div>
</div>
<div class="loading" style="display:none">Loading&#8230;</div>
<script>
$( document ).ready(function() {
	$(document).on("click",".star.rating",function(e) {
        let starEl = e.currentTarget;
		console.log(starEl.parentNode.dataset.stars + ", " + starEl.dataset.rating);
		starEl.parentNode.setAttribute('data-stars', starEl.dataset.rating);
    });
	loadpaginate(1,'#latest');
	loadpaginate(1,'#withapp');
	loadpaginate(1,'#forapp');
	loadpaginate(1,'#completed');
	
	$('.btnsearch').click(function(){
		loadpaginate(1,'#'+$(this).parents('.tab-pane').attr('id'));
	});

	$('.searchkey').keypress(function (e) {
		var key = e.which;
		if(key == 13){
			loadpaginate(1, '#'+$(this).parents('.tab-pane').attr('id'));
			return false;  
		}
	}); 
	
	$('#btn-delete').click(function(){
		var btn = $('#btn-delete');
		var task = $('.delete-selected').attr('data-id');
		
		$('#btn-delete').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');
		
		$.ajax({
			url: '/project-owner/task/delete',
			method: 'POST',
			data: {task:task,container:'#'+$(this).parents('.tab-pane').attr('id')},
		}).done(function(data) {
			$('#btn-delete').html('Yes');
			$('.task-box'+data.task).remove();
			loadpaginate(1,data.container);
			$('.bd-modal-delete').modal('hide');
		});
		
	});


	$(document).on("click",".btnSetCompleted",function() {
		var task_id = $(this).attr('data');
		 bootbox.confirm("Are you sure you want to set this task to completed?", function(result){
	        	completetask(task_id);
	    	}) 
		return false;
	});
	
	$('#addproTask').on('show.bs.modal', function (e) {
		$('#frm_project').show();
		$('.steps').hide();
		$('.step-active').removeClass('step-active');
		$('.steps:first').addClass('step-active').show();
		$('.container-success').hide();
		$('#legalfile ul').html('');
		$('#title').focus();
	});
	
	$('#addproTask').on('hidden.bs.modal', function (e) {
		$('.txt').val('');
		$('#description').trumbowyg('empty');
		$('#progress_profile .progress-bar').css('width',0 + '%');
		$('#progress_profile').attr('data-percent', 0 + '%');
		$('#progress_profile').hide();
		$('#frm_project').show();
		$('.container-success').hide();
		$('#verification').val('I will verify this task through photos and videos of the completed service.');
	});
	
	$('#addtaskform').submit(function (e) {
		e.preventDefault();
			$('#btnSubmit').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span>');
			$('#addtaskform').find('#description').val($('#description').trumbowyg('html'));
			
			var datastring = $(this).serialize();
			
			$.ajax({
				url: '/project-owner/task/save',
				method: 'POST',
				data: datastring,
				//dataType: "json",
				beforeSend: function() {
					//btn.val(btn.data('original-text'));
				}
			}).done(function(data) {
				$('#btnSubmit').html('Submit');
				if(data.status){
					$('#addproTask').modal('hide');
					Swal.fire({
						type: 'success',
						title:'Success',
						text: 'Task has been saved successfully',
						showConfirmButton: false,
						timer: 1500
					});
					
					if(data.container!=''){
						loadpaginate(1,data.container);
					}else{
						loadpaginate(1,'#latest');
					}
					
					$('.task-box'+data.task).replaceWith(data.record); //replace other tabs
					initEditDelete();
				}else{
					Swal.fire({
						type: 'error',
						title:'Task Error',
						text: data.msg,
						showConfirmButton: false,
						timer: 1500
					});
				}
			});
			
	});

	$('#profileupload').fileupload({
			url: '/project-owner/task/uploadphoto',
			dataType: 'json',
			done: function (e, data) {
				$.each(data.result.files, function (index, file) {
					if (file.error){
						alert(file.error+' - for uploaded file');
						var progress =0;
						
						$('#task_image .progress-bar').css(
							'width',
							progress + '%'
						);
						$('#progress_profile').attr('data-percent', progress + '%');
						$('#profile_photo_file').val('');
					}else {
						$('#profile_photo_file').attr('src',file.url);
						$('#profile_photo_file').show();
						$('#task_img').val('/uploads/tasks/'+file.name);
					}
				});	
			},
			progressall: function (e, data) {
				$('#task_image').show();
					var progress = parseInt(data.loaded / data.total * 100, 10);
					$('#task_image .progress-bar').css(
							'width',
							progress + '%'
					);
					$('#task_image').attr('data-percent', progress + '%');
			}
	}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	
});



function loadpaginate(pages,container) {
	var searchkey = $(container).find('.searchkey').val();
	$.ajax({
		url: '/project-owner/task/load',
		type: 'POST',
		dataType: 'json',
		data: {project:'<?=$project["id"]?>', pages: pages, search_key:searchkey, container:container },
		beforeSend: function () {
			loader();
		},
		success: function (data) {
			$(data.container+'-tab .count').html('('+data.total_count+')');
			$(data.container+' .task-box').remove();
			$(data.container).append(data.html);
			$(data.container+' .pagination-container').html(data.pagination);
			
			$(data.container+' .task-box').each(function(){
				$('.task-box'+$(this).attr('data-id')).replaceWith($(this)[0].outerHTML); //replace other tabs
			});
			
			$('.loader-box').hide();
			initEditDelete();
		},
		error: function () {
			Swal.fire({
				type: 'error',
				title:'Task Error',
				text: 'Something went Wrong. Please Reload The Page , Sorry for Inconvience',
				showConfirmButton: false,
				timer: 1500
			});
		}
	});
}

function initEditDelete(){
	$('.edit-task').off('click').on('click',function(){
		$.ajax({
			url: '/project-owner/task/get',
			type: 'POST',
			dataType: 'json',
			data: { task: $(this).attr('data-id'),container:'#'+$(this).parents('.tab-pane').attr('id') },
			beforeSend: function () {
				loader();
			},
			success: function (data) {
				if(data.status){
					$('.loader-box').hide();
					$('#addproTask').modal('show');
					$('#task').val(data.task.id);
					$('#title').val(data.task.title);
					$('#project_type').val(data.task.type_id);
					$('#payment').val(data.task.payment);
					$('#description').val(data.task.description);
					$('#description').trumbowyg('html', data.task.description);
					$('#goal_date').val(data.task.goal_date);
					$('#task_img').val(data.task.image);
					$('#profile_photo_file').prop('src',data.task.image);
					
					$('#container').val(data.container);
					
					$('#verification').val(data.task.verification);
					
					$('#esh_value').removeAttr('required');
					$('#cash_value').removeAttr('required');
					$('.values').hide();
					
					$('#cash_value').val(data.task.cash_value);
					$('#esh_value').val(data.task.esh_value);
					if(data.task.payment=='cash'){
						$('#cash_value').attr('required', 'required');
						$('.cash_value').show();
					}else if(data.task.payment=='equity'){
						$('#esh_value').attr('required', 'required');
						$('.esh_value').show();
					}else{
						$('#esh_value').attr('required', 'required');
						$('#cash_value').attr('required', 'required');
						$('.values').show();
					}
					
					$('#legalfile ul').html('');
					if(data.task.legal_file!=''){
						var f = data.task.legal_file.split("/");
						filename = f[f.length-1];
						var li = '<li>'+filename+' &nbsp;<input name="legalfile" type="hidden" value="'+filename+'" /><a class="removefile" href="javascript:;"><span class="badge badge-pill badge-danger">x</span></a></li>';
						$('#legalfile ul').append(li);
					}
					$('.removefile').click(function(){
						$(this).parents('li').remove();
					});
				}
			},
			error: function () {
				Swal.fire({
					type: 'error',
					title:'Task Error',
					text: 'Something went Wrong. Please Reload The Page , Sorry for Inconvience',
					showConfirmButton: false,
					timer: 1500
				});
			}
		});
	});
	
	$('.delete-task').off('click').on('click',function(){
		$('.delete-selected').removeClass('delete-selected');
		$(this).addClass("delete-selected");
		$('.bd-modal-delete').modal('show');
	});
	
	$('.btn-view-message').off('click').on('click',function(){
		$('#viewMessage').modal('show');
		var img = '<img src="'+$(this).attr('data-img')+'" class="mr-2 rounded-circle" alt="" height="40" width="40">';
		$('#viewMessage').find('.modal-title').html(img+' '+$(this).attr('data-name'));
		$('#viewMessage').find('#message-content').html($(this).parent().find('.message').val());
	});
	
	$('.btn-approve').off('click').on('click',function(){
		var btn = $(this);
		btn.html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Processing');
		$.ajax({
			url: '/project-owner/task/approve',
			type: 'POST',
			dataType: 'json',
			data: { application: $(this).attr('data-application'),container:'#'+$(this).parents('.tab-pane').attr('id') },
			beforeSend: function () {
				
			},
			success: function (data) {
				btn.html('<i class="far fa-hand-pointer"></i>&nbsp;Approve');
				if(data.status){
					Swal.fire({
						type: 'success',
						title:'Success',
						text: 'Applications has been successfully approved',
						showConfirmButton: false,
						timer: 1500
					});
					
					loadpaginate(1,'#latest');
					loadpaginate(1,'#withapp');
					loadpaginate(1,'#forapp');
					loadpaginate(1,'#completed');
				}else{
					ErrorApplication();
				}
			},
			error: ErrorApplication
		});
	});
}

function ErrorApplication(){
	Swal.fire({
		type: 'error',
		title:'Applications',
		text: 'Something went Wrong. Please Reload The Page , Sorry for Inconvience',
		showConfirmButton: false,
		timer: 1500
	});
}

function loader(){
	$('.loader-box').show();
}

$(function () {
	'use strict';
	$('#legalfileupload').fileupload({
        url: '/project-owner/task/uploadfile',
        dataType: 'json',
        done: function (e, data) {
			$.each(data.result.files, function (index, file) {
				if (file.error){
					alert(file.error+' - for uploaded file');
					var progress =0;
				  
					$('#progress_profile .progress-bar').css(
						'width',
						progress + '%'
					);
					$('#progress_profile').attr('data-percent', progress + '%');
				}else {
					$('#profile_photo_file').attr('src',file.url);
					$('#profile_photo_file').show();
					$('#profile').val(file.name);
					$('#legalfile ul li').remove();
					var li = '<li>'+file.name+' &nbsp;<input name="legalfile" type="hidden" value="'+file.url+'" /><a class="removefile" href="javascript:;"><span class="badge badge-pill badge-danger">x</span></a></li>';
					$('#legalfile ul').append(li);
				}
			});
            $('.removefile').click(function(){
				$(this).parents('li').remove();
			});
        },
        progressall: function (e, data) {
        	$('#progress_profile').show();
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress_profile .progress-bar').css(
                'width',
                progress + '%'
            );
            $('#progress_profile').attr('data-percent', progress + '%');
        }
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
});

function completetask(task_id){
	  $('.loading').show();
		$.ajax({
			url: '/project-owner/kanban/updatestatus',
			type: 'POST',
			data: {
				'id' : task_id,
				'status' : 'completed'
			},
			'success': function(data) {
				$('.loading').hide();
				if (data.success){
					loadpaginate(1,'#latest');
					loadpaginate(1,'#withapp');
					loadpaginate(1,'#forapp');
					loadpaginate(1,'#completed');
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
      		}
			}
		});
}
</script>

<?php $this->load->view('project-owner/dashboard/footer');?>
