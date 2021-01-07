<?php $this->load->view('dashboard/header');?>
<?php $this->load->view('dashboard/navigation');?>
<style>
.project-container {
    background: #fff;
    padding: 20px 5px 5px;
    border: none;
}
.project-container:hover {
    box-shadow: none;
    transform: none;
}
.project-container .badge {
    width: auto;
}
.title:not(:last-child) {
    margin-bottom: 1.8rem;
}
.assto, .tasdate {
	font-size: 14px;
}
.card-content {
    padding: 1.5rem 1.5rem .5rem;
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
.card {
    background-color: #ffffff;
    border: none;
}
.badge-light {
    color: #212529;
    background: #d1e5fd;
}
.card-footer {
	border-top: none;
}
.card-footer-items .badge {
    color: #fff;
    padding: 1em 1em;
}
.title:not(:last-child) {
    margin-bottom: 1rem;
}
.project-outermost .card-header {
	font-size: 1.2rem;
	font-weight: 600;
	background: #DBE9F2;
}
.task-link {
	color: #455B68;
}
.task-link:hover {
	color: #455B68;
	opacity: .8;
	text-decoration: none;
}
</style>
	<main role="main" class="flex-shrink-0">
		<div class="section-lander">
			<div class="container text-center">			
				<p class="lead mt-4 mb-4 d-none">
					ServiceChain creates and manages secure transactions using blockchain technology to provide business professionals a competitive platform for value creation for the service industry. Get Linked into our ServiceChain and see the power of innovation.
				</p>
				<h4>Find Tasks In Your Area Now!</h4>
				<div class="form-box pb-0">					
					<form class="form">
						<div class="form-row">
							<div class="form-group col-md-7">
								<input type="text" class="form-control form-control-lg" id="search_key" placeholder="">
							</div>
							<div class="form-group col-md-3">
								<div class="form-group">
								    <select class="form-control form-control-lg" id="search_sort">
									<option>Sort by ... </option>
									<option value="latest">Date Latest</option>
									<option value="oldest">Date Oldest</option>
									<option value="amount-highest">Amount highest to lowest</option>
									<option value="amount-lowest">Amount lowest to highest</option>
									<option value="alphabetical-az">Title Aplhabetical ASC</option>
									<option value="alphabetical-za">Title Aplhabetical DESC</option>
								    </select>
								  </div>
							</div>						
							<div class="form-group col-md-2">
								<a href="javascript:getTasksLatest(1)" class="btn btn-block btn-secondary btn-lg">Search</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="section-sub section-services">
			<div class="container services-container">
				<div class="row justify-content-center">
					<div class="sec-heading col-md-12 text-center mb-5" style="display:none;">
						<h2>Our Latest Projects</h2>
					</div>
					<div class="task-loader-loading" style="display: none">
						<div class="text-center">
                              <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                              </div>
                            </div>
					</div>			
					<div  class="row" id="taskcontainer" style="width:100%;">
					</div>
						
				</div>
			</div>
		</div>
		
	</main>
<?php $this->load->view('dashboard/footer');?>
<script src="/assets/js/dashboard/dashboard.js"></script>
<script>
$(document).ready( function () {
	getTasksLatest(1);
});
</script>
	
	
	