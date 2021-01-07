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
			<li class="breadcrumb-item active" aria-current="page">Project Owners</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Project Owners</li>
		</ol>
	</div>
	<?php endif?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>
<style>
.poclist {
	background: #fafafa;
	border: 1px solid #eaeaea;
	padding: 10px 10px 5px;
	border-radius: 3px;
	margin-bottom: 15px;
}
.badge {
	padding: .5rem 1rem;
	margin-top: 5px;
}
.poc-inner a:hover {
	text-decoration: none;
	color: #C69C6D;
}
.poc-name {
	font-weight: 600;
	margin-top: 3px;
}
.poc-desc {
	font-weight: 600;
}
.poc-value {
	font-weight: 600;
}
.poc-inner .alert {
	margin-bottom: 5px;
}
.alert-light {
	border: 1px solid #eaeaea;
}
</style>
<div class="page-container">
	<div class="container project-owner-container">
		<div class="row o-gutters">
			<div class="col-md-12">
				<nav class="page-next pagination-container">
					  
				</nav>
			</div>
			<div class="col-md-12">
				<!--<div class="col-md-12 loader-box" style="">
					<div class="load-container text-center"> <img src="http://cdn.vnoc.com/cowork/spinner-1.gif" height="120"> </div>
				</div>-->
				<!--<div id="load_data_message" style="" class="col-md-12 text-center loader-box">
						<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>
					</div>-->
				<div class="poc-inner">
					<ul id="owners" class="list-unstyled">
						
					</ul>
					<div id="load_data_message" class="col-md-12 text-center"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var current_page = 0;
var last_page = 0;
$( document ).ready(function() {
	loadpaginate(1);
	$(window).scroll(function() {
        if($(window).scrollTop() == $(document).height() - $(window).height()) {
			if(current_page<=last_page){
				loadpaginate(current_page);
			}
        }
    });
});
function loadpaginate(pages) {
	if(pages==1)
		$('#owners').html(' ');
	$.ajax({
		url: '/projectowners/load',
		type: 'POST',
		dataType: 'json',
		data: {pages: pages, search_key:''},
		beforeSend: function () {
			loader();
		},
		success: function (data) {
			//$('#owners li').remove();
			//$('#owners').html(data.html);
			$('#owners').append(data.html);
			//$('.loader-box').hide();
			$('#load_data_message').html('');
			//$('.pagination-container').html(data.pagination);
			
			//last_page = data.pages_count;
			//current_page = data.current_page+1;
		},
		error: function () {
			Swal.fire({
				type: 'error',
				title:'Project owners Error',
				text: 'Something went Wrong. Please Reload The Page , Sorry for Inconvience',
				showConfirmButton: false,
				timer: 1500
			});
		}
	});
}
function loader(){
	//$('.loader-box').show();
	$('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
}
</script>
<?php $this->load->view('project-owner/dashboard/footer');?>