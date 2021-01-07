<body class="d-flex flex-column h-100">
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-darker">
			<div class="container">
				<a class="navbar-brand" href="/project-owner/dashboard"> <img src="<?php echo $this->config->item('site_logo')?>" class="img-logo" width="200"> </a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
				<div class="collapse navbar-collapse" id="navbarsExample07">
					<div class="mr-auto"></div>
					<ul class="navbar-nav">
						<li class="nav-item <?php if ($this->router->fetch_class()=='dashboard') echo 'active'?>"> <a class="nav-link" href="/project-owner/dashboard">Dashboard</a> </li>
				    <li class="nav-item <?php if ($this->router->fetch_class()=='myprojects') echo 'active'?>"> <a class="nav-link" href="/project-owner/myprojects">My Projects</a> </li>
        		<li class="nav-item <?php if ($this->router->fetch_class()=='kanban') echo 'active'?>"> <a class="nav-link" href="/project-owner/kanban">Kanban</a> </li>	
						<li class="nav-item dropdown"> 
               <a class="nav-link <?php if (($this->router->fetch_class()=='projects') || ($this->router->fetch_class()=='tasks') || ($this->router->fetch_class()=='people')) echo 'active'?> dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Marketplace
							 </a>
               <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="/projects">Projects</a>
  								<div class="dropdown-divider"></div>
  								<a class="dropdown-item" href="/tasks">Tasks</a>
  								<div class="dropdown-divider"></div>
  								<a class="dropdown-item" href="/people">Service Providers</a>
  						</div>
            </li>
						<li class="nav-item dropdown"> 
						    <a class="nav-link dropdown-toggle" id="notifydropdown" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="noticount">
								<span class="badge badge-light" id="notification_count"></span>
							</span>
							<i class="fas fa-bell" aria-hidden="true"></i>
						    </a> 
						    <div class="noti-ddm dropdown-menu dropdown-menu-right">
								<div class="notibody">
									
									
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="menu-img-user-container">
								        <?php if ($this->membersdata->getinfobyid('profile_image',$this->session->userdata('userid'))==null ):?>
										<img class="img-user rounded-circle" src="http://cdn.vnoc.com/servicechain/user-default.png" alt="" height="28" width="28">
										<?php else:?>
										<img class="img-user rounded-circle" src="<?php echo $this->membersdata->getinfobyid('profile_image',$this->session->userdata('userid'))?>" alt="" height="28" width="28">
										<?php endif?>
								</span>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            	<a class="dropdown-item" href="/project-owner/profile/<?php echo $this->session->userdata('username')?>">View Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/project-owner/settings">Settings</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/project-owner/cryptoaccount">Wallet</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/history">History</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/project-owner/signout">Logout</a>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>
	
	<script>

	function loadnotifications(){
		$.ajax({
			  url:"/project-owner/notifications/loadnotifications",
			  method:"POST",
			  data:{},
			  cache:false,
			  success:function(data)
			  {
			    if (data.count > 0){
				  $('#notification_count').html(data.count);    
			    }else {
			    	$('#notification_count').html('');
			    }
			    $('.notibody').html(data.html); 
			 }
		 });

	}

	function updatenotifications(){
		$.ajax({
			  url:"/project-owner/notifications/updateclick",
			  method:"POST",
			  data:{},
			  cache:false,
			  success:function(data)
			  {
				  $('#notification_count').html('');
			 }
		 });

	}

	$(document).ready(function(){
		loadnotifications();

		 setInterval(
				 function(){ 
					 loadnotifications();
		 				}, 
		30000);

		 $(document).on("click","#notifydropdown",function() {
			   updatenotifications();  
		        return false;
		    });	
	});
	
	
	</script>
	
	