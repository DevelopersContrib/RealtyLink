<?php $this->load->view('home/header');?>
<?php $this->load->view('home/navigation');?>
<style>
.projtop {
	width: 100%;
	background: #fafafa;
	padding: 20px;
	border-radius: 3px;
}
.projtop a:hover {
	text-decoration: none;
}
</style>
	<!-- Begin page content -->
	<main role="main" class="flex-shrink-0">
		<div class="section-lander sectest">
			<div class="container text-center">
				<h1 class="mt-5 mb-5">
					A Transparent Contribution Platform for Digital Assets on the Blockchain				
				</h1>
				<p class="lead mt-4 mb-4 d-none">
					ServiceChain creates and manages secure transactions using blockchain technology to provide business professionals a competitive platform for value creation for the service industry. Get Linked into our ServiceChain and see the power of innovation.
				</p>
				<h4>Find Projects In Your Area Now!</h4>
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
								<a href="javascript:getTasksLatest(1,'home')" class="btn btn-block btn-secondary btn-lg">Search</a>
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
					<div  class="row" id="taskcontainer">
					</div>
						
				</div>
			</div>
		</div>
		
		<!-- -->
		<div class="section-sub section-contractors">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center mb-3">
						<h2>Latest Projects</h2>
					</div>
					<div class="col-md-12" id="projectscontainer">
					</div>
				</div>
			</div>
		</div>
		<script src="/assets/js/dashboard/dashboard.js"></script>
        <script>
        $(document).ready( function () {
        	getTasksLatest(1,'home');
        	getProjects();
        });
        </script>
		<!-- -->
		<div class="section-cities d-none">
                <div class="container">
                        <div class="row">
                                <div class="col-md-12 text-center">
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/AL" target="_blank">Alabama</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/AK" target="_blank">Alaska</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/AZ" target="_blank">Arizona</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/AR" target="_blank">Arkansas</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/CA" target="_blank">California</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/CO" target="_blank">Colorado</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/CT" target="_blank">Connecticut</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/DE" target="_blank">Delaware</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/FL" target="_blank">Florida</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/GA" target="_blank">Georgia</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/HI" target="_blank">Hawaii</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/ID" target="_blank">Idaho</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/IL" target="_blank">Illinois states</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/IN" target="_blank">Indiana</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/IA" target="_blank">Iowa</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/KS" target="_blank">Kansas</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/KY" target="_blank">Kentucky</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/LA" target="_blank">Louisiana</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/ME" target="_blank">Maine</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MD" target="_blank">Maryland</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MA" target="_blank">Massachusetts</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MI" target="_blank">Michigan</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MN" target="_blank">Minnesota</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MS" target="_blank">Mississippi</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MO" target="_blank">Missouri</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/MT" target="_blank">Montana</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NE" target="_blank">Nebraska</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NV" target="_blank">Nevada</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NH" target="_blank">New Hampshire</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NJ" target="_blank">New Jersey</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NM" target="_blank">New Mexico</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NY" target="_blank">New York</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/NC" target="_blank">North Carolina</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/ND" target="_blank">North Dakota</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/OH" target="_blank">Ohio</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/OK" target="_blank">Oklahoma State</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/OR" target="_blank">Oregon</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/PA" target="_blank">Pennsylvania</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/RI" target="_blank">Rhode Island</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/SC" target="_blank">South Carolina</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/SD" target="_blank">South Dakota</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/TN" target="_blank">Tennessee</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/TX" target="_blank">Texas</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/UT" target="_blank">Utah</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/VT" target="_blank">Vermont states</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/VA" target="_blank">Virginia</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/WA" target="_blank">Washington</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/WV" target="_blank">West Virginia</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/WI" target="_blank">Wisconsin</a>&nbsp;|&nbsp;
					    <a class="wrap-cityName-a" href="http://handyman.com/contractor/find/city/WY" target="_blank">Wyoming</a>&nbsp;|&nbsp;
				  </div>
                        </div>
                </div>
        </div>
		<!-- -->
	</main>
	
<?php $this->load->view('home/footer');?>
	