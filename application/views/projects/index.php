<?php if ($this->session->userdata('logged_in')):?>

   <?php if ($this->session->userdata('page')=='contractor'):?>
    <?php $this->load->view('dashboard/header');?>
    <?php $this->load->view('dashboard/navigation');?>
    <?php else:?>
    <?php $this->load->view('project-owner/dashboard/header');?>
    <?php $this->load->view('project-owner/dashboard/navigation');?>
   <?php endif?> 

<?php if ($this->session->userdata('page')=='contractor'):?>
    <div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Projects</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Projects</li>
		</ol>
	</div>
	<?php endif?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>

<link rel="stylesheet" href="/assets/css/project.css">

<div class="page-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<form class="form search-project-form" action="javascript:void(0)">
					<div class="form-row">
						<div class="form-group col-md-7">
							<input type="text" class="form-control" id="search_key" placeholder="Search Project Here ... ">
						</div>
						<div class="form-group col-md-3">
							<div class="form-group">
							    <select class="form-control" id="filter_status">
								<option value="">Status</option>
								<option value="new">New</option>
								<option value="in progress">In Progress</option>
								<option value="completed">Completed</option>
							    </select>
							  </div>
						</div>						
						<div class="form-group col-md-2">
							<a href="javascript:void(0)" class="btn btn-block btn-secondary btnSearch"><b>Search<b/></a>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"  id="load_data">
				
			</div>
			<div id="load_data_message" class="col-md-12 text-center"></div>
		</div>
	</div>
</div>

<?php $this->load->view('dashboard/footer');?>	
<script src="/assets/js/projects/projects.js"></script>
<script>     
$(document).ready(function(){
    var limit = 7;
    var start = 0;
    var action = 'inactive';

    if(action == 'inactive')
    {
    action = 'active';
    load_projects(limit, start);
    }

    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
        $('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
        action = 'active';
        start = start + limit;
        load_projects(limit, start);
        
        }
    });

    $(".btnSearch").on("click",function() {
    	$('#load_data').html('');
    	load_projects(7,0);
    });

	jQuery('#search_key').keypress(function(e){
		if(e.keyCode==13){
			$('#load_data').html('');
	    	load_projects(7, 0);
		}
	});
  
});
</script>