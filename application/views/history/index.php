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
			<li class="breadcrumb-item active" aria-current="page">Your History</li>
		</ol>
	</div>
	<?php else:?>
	<div class="breadcrumb-outer">
		<ol class="container breadcrumb">
			<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
			<li class="breadcrumb-item active" aria-current="page">Your History</li>
		</ol>
	</div>
	<?php endif;?>
    <?php else:?>
    <?php $this->load->view('home/header');?>
    <?php $this->load->view('home/navigation');?>
    
<?php endif?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.6/css/fixedHeader.bootstrap4.min.css ">
<script src=" https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.6/js/dataTables.fixedHeader.min.js "></script>
<style>
.wallet-container {
	background: #ffffff;
	border: 1px solid #eaeaea;
	padding: 40px 10px 20px;
	border-radius: 3px;
}
@media (max-width: 1199.98px) {
	.table-box {
		overflow-x: scroll;
	}
}
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #C69C6D;
    border-color: #C69C6D;
}
table th:nth-child(1), table td:nth-child(1) {
	width: 150px;
}
table th:nth-child(3), table td:nth-child(3) {
	width: 200px;
}
</style>


<div class="page-container">
	<div class="container">	
		<div class="row justify-content-center">
			<div class="col-lg-12">
				<h3 class="text-secondary">
					Your History
				</h3>
			</div>
			<div class="col-md-12 wallet-container">
				<div class="col-lg-12">
					<div class="card newsfeed-card mb-3">
						<div class="card-body px-0">
							<table id="transaction-table" class="table table-striped table-bordered table-minimal table-hover dt-responsive" style="width:100%">
								<thead>
									<tr>
										<th>Date</th>
										<th>Message</th>
										<th>IP Address</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
	
<script>
	$(document).ready(function() {
        var $Ttables = $('#transaction-table').DataTable({
			"order": [[0, "desc"]],
			"language": {
				"lengthMenu": "Displaying _MENU_ tasks",
				paginate: {
					next: '<i class="fas fa-chevron-right"></i>', // or '→'
					previous: '<i class="fas fa-chevron-left"></i>' // or '←' 
				}
			},
			"columnDefs": [
				{ 
					"searchable": true,
					"visible":true,
					"targets": 0
				},
				{ 
					"searchable": true,
					"visible":true,
					"targets": 1
				},
				{ 
					"searchable": false,
					"visible":true,
					"targets": 2
				}
			],
			"processing": true,
			"serverSide": true,
			"ajax":"/history/table"
		});

    });
</script>
<?php $this->load->view('dashboard/footer');?>