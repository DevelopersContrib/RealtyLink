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
			<li class="breadcrumb-item active" aria-current="page">People</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">People</li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>

<style>
.equal {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  flex-wrap: wrap;
}
.equal > [class*='col'] {
  display: flex;
  flex-direction: column;
}
.poclist {
	background: #fafafa;
	padding: 10px 10px 5px;
	border-radius: 3px;
	margin-bottom: 10px;
}
.poclist:hover {
	box-shadow: 0px 0px 5px #F7D1A7;
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
	font-weight: 300;
	margin-top: 5px;
	font-size: .85rem;
}
.poc-desc {
	font-weight: 300;
	font-size: .9rem;
}
.poc-value {
	font-weight: 800;
	font-size: .85rem;
	color: #999;
}
.poc-inner .alert {
	margin-bottom: 5px;
	padding: .65rem .35rem;
}
.poc-inner .col {
	padding-right: 9px;
	padding-left: 9px;
}
.alert-light {
	border: none;
}
.page-footer {
	background: #fafafa;
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
				<!--<div id="load_data_message" style="" class="col-md-12 text-center loader-box">
					<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>
				</div>-->
				<div class="poc-inner mt-1">
					<ul id="peoples" class="list-unstyled">
						
						
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
		$('#peoples').html(' ');
	$.ajax({
		url: '/people/load',
		type: 'POST',
		dataType: 'json',
		data: {pages: pages, search_key:''},
		beforeSend: function () {
			loader();
		},
		success: function (data) {
			//$('#peoples li').remove();
			//$('#peoples').html(data.html);
			$('#peoples').append(data.html);
			//$('.loader-box').hide();
			$('#load_data_message').html('');
			//$('.pagination-container').html(data.pagination);
		},
		error: function () {
			Swal.fire({
				type: 'error',
				title:'Project peoples',
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

<?php $this->load->view('dashboard/footer');?>