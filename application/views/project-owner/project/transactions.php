<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<link rel="stylesheet" href="/assets/css/loading.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap4.min.css ">
<script src=" https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js "></script>
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
.temail {
	width: 165px;
	overflow: hidden;	
}
.ttid {	
	width: 140px;
	overflow: hidden;	
}
.tdetails {
	position: relative;
	width: 200px;
	overflow: hidden;	
}
.table-outer {
	background: #fff;
	padding: 20px 10px 20px;
	border: 1px solid #eaeaea;
	border-radius: 3px;
}
@media (max-width: 1199.98px) {
	.table-box {
		overflow-x: scroll;
	}
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
					<h3><?=ucwords($project['title'])?> Blockchain Transactions</h3>
				</div>					
				<div class="row project-container">
					
					<!-- <div id="load_data_message" style="" class="col-md-12 text-center loader-box">
						<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>
					</div> -->
					<div class="col-md-7">
						<div class="task-heading">
							<p><?=stripslashes($project['description'])?></p>
						</div>						
					</div>	
					<!-- <div class="col-md-5 text-right mb-3">
						<a href="javascript:;" data-toggle="modal" data-target="#addproTask" class="btn btn-secondary">
							<i class="fas fa-plus" aria-hidden="true"></i> Add Task
						</a>
						<a href="/project/details/<?=$project['id']?>/<?=$project['slug']?>" target="_blank" class="btn btn-warning">
							Public Page
						</a>
						<a href="/project-owner/project/transactions/<?=$project['id']?>" target="_blank" class="btn btn-primary">
							Transactions
						</a>
					</div> -->
					
					<!-- diri ibutang ang tab bai -->
					<div class="col-md-12">
						<div class="tab-container">
							<ul class="nav nav-tabs nav-justified" id="taskTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="contracts-tab" data-toggle="tab" href="#contracts"><i class="far fa-list-alt"></i>&nbsp;Contracts</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="tasks-tab" data-toggle="tab" href="#tasks" role="tab"><i class="far fa-file-alt"></i>&nbsp;Tasks</span></a>
								</li>
							</ul>
							<div class="tab-content" id="taskTabContent">
								<div class="tab-pane fade show active" id="contracts" role="tabpanel">
									<div class="col-md-12 table-outer">
										<div class="table-box">
										<!-- -->
											<table id="contractstable" class="table table-striped table-bordered nowrap" style="width:100%;">
												<thead>
												<tr>
													<th>Type</th>
													<th>Address</th>
													<th>Trand ID</th>
													<th>Date Created</th>
												</tr>
												</thead>
												<tbody>
																				
												</tbody>
												
											</table>
										<!-- -->
										</div>
									</div>
										<!-- -->
								</div>
								<div class="tab-pane fade" id="tasks" role="tabpanel">
									 <!-- <div class="col-md-12 mt-3 mb-3">
									     <h4>Transaction List</h4>
									 </div> -->
									 <div class="col-md-12 table-outer">
									     <div class="table-box">
										  <!-- -->
										  <table id="transtable" class="table table-striped table-bordered nowrap" style="width:100%">
										      <thead>
											   <tr>
												<th>Name</th>
												<th>Transaction ID</th>
												<th>Amount</th>
												<th>Currency</th>
												<th>Notes</th>
												<th>Date</th>
												<th>Status</th>
											   </tr>
										      </thead>
										      <tbody>
																		      
										      </tbody>
										      
										  </table>
										  <!-- -->
									     </div>
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
<!-- <div class="modal fade bd-modal-delete" tabindex="-1" role="dialog" aria-labelledby="" >
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
</div> -->
<!-- Add Task Modal-->
<!-- <div class="modal fade" tabindex="-1" role="dialog" id="addproTask">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Task</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
			</div>
			<div class="modal-body">
				<?php //$this->load->view('project-owner/task/task_form')?>
			</div>				
		</div>
	</div>
</div> -->

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
var contractstable = $('#contractstable').dataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/project-owner/project/contractslist",
        "data": function ( d ) {
            d.project_id = '<?=$project['id']?>';
            // d.custom = $('#myInput').val();
            // etc
        }
    },
    "order": [[0, 'ASC']],
    "columns": [
        {
            "orderable": true,
            "searchable": true,
            /* "render": (data, type, row) => {
                return '<div class="temail text-break text-truncate">'+row[0]+' '+row[1]+'</div>';
            } */
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {                
				return `<a href="${row[4] === 'test' ? 'https://ropsten.etherscan.io/address/'+row[1]:'https://etherscan.io/address/'+row[1]}" target="_blank">${row[1]}</a>`;
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {                
				if(row[2]) {
					return `<a href="${row[4] === 'test' ? 'https://ropsten.etherscan.io/tx/'+row[2]:'https://etherscan.io//tx/'+row[2]}" target="_blank">${row[2]}</a>`;
				} else {
					return 'No Transaction ID';
				}
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            /* "render": (data, type, row) => {
                return '<div class="tcurrency">'+row[4]+'</div>';
            } */
            
        },
    ]
});
var transtable = $('#transtable').dataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        "url": "/project-owner/project/translist",
        "data": function ( d ) {
            d.project_id = '<?=$project['id']?>';
            // d.custom = $('#myInput').val();
            // etc
        }
    },
    "order": [[0, 'ASC']],
    "columns": [
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="temail text-break text-truncate">'+row[0]+' '+row[1]+'</div>';
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                if (row[8]=='test'){
                    return '<div class="ttid text-break text-truncate"><a href="https://ropsten.etherscan.io/tx/'+row[2]+'" target="_blank">'+row[2]+'</a></div>';
                }else {
                        return '<div class="ttid text-break text-truncate"><a href="https://etherscan.io/tx/'+row[2]+'" target="_blank">'+row[2]+'</a></div>'; 
                }
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="tamount">'+row[3]+'</div>';
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="tcurrency">'+row[4]+'</div>';
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="tdetails text-break text-truncate"><a href="task/updates/'+row[9]+'" target="_blank">'+row[5]+'</a></div>';
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="tdate">'+row[6]+'</div>';
            }
            
        },
        {
            "orderable": true,
            "searchable": true,
            "render": (data, type, row) => {
                return '<div class="tblock">'+row[7]+'</div>';
            }
            
        }
        
        
    ]
});
</script>

<?php $this->load->view('project-owner/dashboard/footer');?>
