<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<link href="/assets/js/vendor/Trumbowyg/ui/trumbowyg.min.css" rel="stylesheet">	
<script src="/assets/js/vendor/Trumbowyg/trumbowyg.min.js"></script>

<!-- breadcrumb -->
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">My Projects</li>
		</ol>
	</div>
<style>
.txt-alert {
        position: absolute;
        font-size: 12px;
        color: red;
        bottom: -11px;
        background: #fff;
        padding: 0px 3px 0px 1px;
}
</style>

	<!-- <div class="page-container" style="min-height: 548px;"> -->
	<div class="page-container">
		<div class="container">
			<div class="row">
				<div class="col-md-12 mb-2">
						<div class="project-heading row">
							<div class="col-md-6 text-left">
								<h3>Projects</h3> 
							</div>
							<div class="col-md-6 text-right">
								<a href="javascript:loadform(0)" class="btn btn-secondary" title="edit" ><i class="fas fa-plus"></i>&nbsp;Add Project</a>
							</div>
						</div>			
				</div>
				<?php if ($count_projects==0):?>
				<div class="alert alert-warning" role="alert" id="no-project-error">
				     You don't have any projects in your account.
			     </div>
				<?php endif;?>
				<div class="col-md-12">
					<div class="row" id="load_data">
					
					</div>
				</div>
				<div id="load_data_message" class="col-md-12 text-center"></div>
			</div>
		</div>
	</div>
	<!-- Add Project Modal-->
	<div class="modal" tabindex="-1" role="dialog" id="addproModal">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Project</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body" id="modal-form-content">
					
				</div>				
			</div>
		</div>
	</div>

<?php $this->load->view('project-owner/dashboard/crypto_forms');?>
<?php $this->load->view('project-owner/dashboard/footer');?>
<script src="/assets/js/project-owner/myprojects/myprojects.js"></script>
<script>
var clickVendor = false;
$(document).ready(function(){
    var limit = 5;
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

	
	$(document).on("click",".vendor",function() {
		clickVendor = true;
		//$(document).off("click",".project-container");
        //return true;
    });
    $(document).on("click",".project-container",function() {
		//if(clickVendor) return false;
        var id= $(this).attr('id');
        var slug = $(this).attr('data');
        //window.location = '/project-owner/project/'+id+'/'+slug;
		setTimeout(function(){
			if(clickVendor){
				clickVendor = false;
				return false;
			}else{
				r(id,slug);
			}
		},200);
    });
	
	
});
function r(id, slug){
	window.location = '/project-owner/project/'+id+'/'+slug;
}
</script>
	
	