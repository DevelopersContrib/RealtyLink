<?php if ($this->session->userdata('logged_in')):?>
    <?php if ($this->session->userdata('page')=='contractor'):?>
          <?php $this->load->view('dashboard/header');?>
          <?php $this->load->view('dashboard/navigation');?>
          <?php else:?>
          <?php $this->load->view('project-owner/dashboard/header');?>
          <?php $this->load->view('project-owner/dashboard/navigation');?>
    <?endif?>
   <?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Rating</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Rating</li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>
<link rel="stylesheet" href="/assets/css/star.css">
<div class="page-container">
	<div class="container">	
		<div class="row justify-content-center">
			<div class="col-lg-12 text-center py-5">
				<h3>
					<small>Rate this</small> <a target="_blank" href="/project-owner/profile/<?=$username?>"><?=ucwords($owner_name)?></a> <small>for task</small> 
					<a target="_blank" href="/tasks/details/<?=$task_id?>/<?=$slug?>"><?=$task_title?></a>
				</h3>
			</div>
			<div id="rating" class="stars justify-content-center" data-stars="5" data-task="<?=$task_id?>">
				<svg height="25" width="23" class="star rating" data-rating="1">
				<polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
			  </svg>
			  <svg height="25" width="23" class="star rating" data-rating="2">
				<polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
			  </svg>
			  <svg height="25" width="23" class="star rating" data-rating="3">
				<polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
			  </svg>
			  <svg height="25" width="23" class="star rating" data-rating="4">
				<polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
			  </svg>
			  <svg height="25" width="23" class="star rating" data-rating="5">
				<polygon points="9.9, 1.1, 3.3, 21.78, 19.8, 8.58, 0, 8.58, 16.5, 21.78" style="fill-rule:nonzero;"/>
			  </svg>
			</div>
			
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group">
					<label for=""> Comment </label>
					<textarea class="form-control" name="" id="comment" rows="5"></textarea>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary" id="submit">Submit</button>
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
$( document ).ready(function() {
	$('#submit').click(function(){
		$('#submit').html('Submit <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>').attr('disabled','disabled');
		$.ajax({
			url: '/rating/rate',
			type: 'POST',
			data: {
				'task' : $('#rating').attr('data-task'),
				'rate' : $('#rating').attr('data-stars'),
				'comment' : $('#comment').val(),
			},
			'success': function(data) {
				$('#submit').html('Submit').attr('disabled',false);
				Swal.fire({
				  type: 'success',
				  title: 'Success',
				  html: data.success_message,
				
				}).then(result => {
					if (result.value) {
					} else {
					}
					
				});
			}
		});
	});
	$(document).on("click",".star.rating",function(e) {
        let starEl = e.currentTarget;
		starEl.parentNode.setAttribute('data-stars', starEl.dataset.rating);
    });
});
</script>
<?php $this->load->view('project-owner/dashboard/footer');?>
