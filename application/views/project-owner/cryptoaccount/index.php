<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
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
.page-item.active .page-link {
    z-index: 1;
    color: #fff;
    background-color: #C69C6D;
    border-color: #C69C6D;
}
</style>

<div class="breadcrumb-outer">
	<ol class="container breadcrumb">
		<li class="breadcrumb-item"><a href="/project-owner/dashboard">Dashboard</a></li>
		<li class="breadcrumb-item active" aria-current="page">Crypto Account</li>
	</ol>
</div>

<div class="page-container">
	<div class="container">
		<div class="row">
			<div class="col-md-12 mb-2">
				<div class="project-heading">
					<div class="row">
						<div class="col-md-6 text-left">
							<h3>Your Wallet  Address</h3> 
						</div>
						<div class="col-md-6 text-right">
							<a href="javascript:void(0);" class="btn btn-secondary" id="btn-generate-wallet" title="generate new" >
								<i class="fas fa-cog"></i>
								Generate New Wallet Address
							</a>
							
						</div>
					</div>						
				</div>					
			</div>
			<div class="col-md-12 wallet-container">
				<div class="row justify-content-center">
					<div class="col-md-8 mb-4">
						<div class="alert alert-danger" role="alert" id="wallet_error" style="display: none">
									This is a primary alert—check it out!
						</div>
							<div class="wallet-body">
								<form>
									<div class="form-group">
									    <label><i class="fas fa-wallet"></i>&nbsp;Wallet Address</label>
									    <input type="text" class="form-control form-control-lg" id="my_wallet_address" value="<?php echo $wallet_address?>" disabled="disabled">
								</form>
							</div>
						</div>
						<div class="col-md-12 text-center">
							<div class="wallet-buttons mb-3">
								<a href="javascript:void(0);" class="btn btn-danger btn-lg mb-3" id="btnw_edit"><i class="fas fa-edit"></i>&nbsp;Edit</a>
								<a href="javascript:void(0);" class="btn btn-info btn-lg mb-3" id="btnw_save" style="display: none"><i class="fas fa-save"></i>&nbsp;Save</a>
								<a href="javascript:void(0);" class="btn btn-warning btn-lg mb-3" id="btnw_copy"><i class="fas fa-clone"></i>&nbsp;Copy Wallet Address</a>
							</div>
							<div class="wallet-notice">
								<div class="text-danger">
									 <small>IMPORTANT THINGS TO NOTE : Your ETH Wallet address should not be generated from Coinbase. It should be an ERC20 wallet that you hold keystore to. </small>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row" id="load_data">
			 
			</div>
			<div id="load_data_message" class="col-md-12 text-center"></div>
		</div>
		<div class="row mt-2">
	        <?php $this->load->view('project-owner/cryptoaccount/transaction_list');?>
		</div>
	</div>
</div>
<?php $this->load->view('project-owner/dashboard/footer');?>
<script>
function copyWallet() {
	 $('#my_wallet_address').attr('disabled',false);
	  var copyText = document.getElementById("my_wallet_address");
	  
	  copyText.select();
	  copyText.setSelectionRange(0, 99999); /*For mobile devices*/
	  document.execCommand("copy");

	  /* Alert the copied text */
	  Swal.fire({
			type: 'success',
			title:'Success',
			text: 'You successfully copied wallet address',
			showConfirmButton: false,
			timer: 1500
		});

	  $('#my_wallet_address').attr('disabled',true);
	}

function saveWallet(){
	$('#wallet_error').hide();
	var address = $('#my_wallet_address').val();
	if (address == ""){
		$('#wallet_error').show();
		$('#wallet_error').html('Please enter wallet address');
		$('#my_wallet_address').focus();
	}else {
		$('#btnw_save').html('Saving <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>').attr('disabled','disabled');
		$.ajax({
			  url:"/project-owner/cryptoaccount/savewallet",
			  method:"POST",
			  data:{address:address},
			  cache:false,
			  success:function(data)
			  {
				   $('#btnw_edit').show();
				   $('#btnw_save').hide();
				   $('#my_wallet_address').attr('disabled',true);

				   Swal.fire({
						type: 'success',
						title:'Success',
						text: 'You successfully updated wallet address',
						showConfirmButton: false,
						timer: 1500
					});

				   $('#btnw_save').html('<i class="fas fa-save"></i>&nbsp;Save').attr('disabled',false);
							   
			  }
			 });
	}
}

function generateNew(){
	$('#btn-generate-wallet').html('Generating wallet address <i class="ace-icon fa fa-spinner fa-spin orange bigger-125"></i>').attr('disabled','disabled');
	$.ajax({
		  url:"/project-owner/cryptoaccount/generatewallet",
		  method:"POST",
		  data:{},
		  cache:false,
		  success:function(data)
		  {
			
			   $('#my_wallet_address').val(data.address)
			   $('#my_wallet_address').attr('disabled',true);

			   Swal.fire({
					type: 'success',
					title:'Success',
					text: 'You successfully generated wallet address',
					showConfirmButton: false,
					timer: 1500
				});

			   $('#btn-generate-wallet').html('<i class="fas fa-cog"></i>&nbsp;Generate New Wallet Address').attr('disabled',false);
						   
		  }
		 });
}
	
$(document).ready(function(){
	$(document).on("click","#btnw_edit",function() {
		   $(this).hide();
		   $('#btnw_save').show();
		   $('#my_wallet_address').attr('disabled',false);
	        return false;
	    });	

	$(document).on("click","#btnw_copy",function() {
		copyWallet();
		return false;
	});

	$(document).on("click","#btnw_save",function() {
		saveWallet();
		return false;
	});

	
	$(document).on("click","#btn-generate-wallet",function() {
		 bootbox.confirm("Are you sure? All tokens from previous wallet address will not be carried on new wallet.", function(result){
	        	generateNew();
	    	}) 
		return false;
	});
	
});
</script>
