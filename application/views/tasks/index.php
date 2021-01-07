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
			<li class="breadcrumb-item active" aria-current="page">Tasks</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Tasks</li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>
<style>
.project {
    margin-bottom: 1rem;
}
</style>
<div class="page-container">
	<div class="container">
		<div class="row">
			<form id="form" class="col-12">
			  <div class="form-row">
				<div class="form-group col-md-9">
				  <input id="search_key" type="text" class="form-control" placeholder="Search">
				</div>
				<div class="form-group col-md-2">
				  <select id="payment" class="form-control">
					<option value="">Filter Payment</option>
					<option value="cash">Cash</option>
					<option value="equity">Equity</option>
					<option value="cash/equity">Cash and Equity</option>
				  </select>
				</div>
				<div class="form-group col-md-1">
				  <button id="btnSearch" type="button" class="btn btn-secondary">Search</button>
				</div>
			  </div>
			</form>
			
		</div>
		<!--<div id="load_data_message" class="col-md-12 text-center">
			<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>
		</div>-->
		
		<div  class="row" id="taskcontainer">

		</div>
		<div id="load_data_message" class="col-md-12 text-center"></div>
	</div>
</div>
<script>
var current_page = 0;
var last_page = 0;
$(document).ready( function () {
	$('#form').submit(function(){
		return false;
	});
	getTasksLatest(1,'home');
	$('#btnSearch').click(function(){
		getTasksLatest(1,'home');
	});
	
	$('#payment').change(function(){
		getTasksLatest(1,'home');
	});
	
	$('#search_key').on('keypress',function(e) {
		if(e.which == 13) {
			getTasksLatest(1,'home');
		}
	});
	
	
    $(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
			//$('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
			// action = 'active';
			// start = start + limit;
			// load_projects(limit, start);
			//current_page++;

			if(current_page<=last_page){
				getTasksLatest(current_page,'home');
			}
        }
    });
});

function getTasksLatest(page = '1',from=''){
	var search_key = $('#search_key').val();
	var sort_by = $('#search_sort').val();
	var payment = $('#payment').val();

	//$('#load_data_message').show();
	$('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
	if(page==1)
		$('#taskcontainer').html(' ');

	$.ajax({
		method: "POST",
		url:  "/taskajax/loadtasks",
		data: { 'page':page,'search_key':search_key,'sort_by':sort_by,'from':from, payment:payment }
	})
	.success(function( data ) {
		//$('#load_data_message').hide();
		//$('#taskcontainer').html(data);
		$('#taskcontainer').append(data);
		$('#load_data_message').html('');
	});
}



</script>

<?php $this->load->view('dashboard/footer');?>