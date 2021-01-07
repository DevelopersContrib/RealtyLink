<body class="d-flex flex-column h-100">
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-darker">
			<div class="container"> 
			<a class="navbar-brand" href="/">
				<img src="<?php echo $this->config->item('site_logo')?>" class="img-logo" width="200">
			</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
				<div class="collapse navbar-collapse" id="navbarsExample07">
					<div class="mr-auto"></div>
					<ul class="navbar-nav">
						<li class="nav-item <?php if ($this->router->fetch_class()=='home') echo 'active'?>"> <a class="nav-link" href="/">Home</a> </li>
						<li class="nav-item <?php if ($this->router->fetch_class()=='projects') echo 'active'?>""> <a class="nav-link" href="/projects" target="_blank">Projects</a> </li>
						<li class="nav-item <?php if ($this->router->fetch_class()=='tasks') echo 'active'?>""> <a class="nav-link" href="/tasks" target="_blank">Tasks</a> </li>
						<li class="nav-item <?php if ($this->router->fetch_class()=='people') echo 'active'?>""> <a class="nav-link" href="/people" target="_blank">Service Providers</a> </li>
						<li class="nav-item"> <a class="nav-link" href="/project-owner" target="_blank">Post a Project</a> </li>
						<li class="nav-item dropdown"> 
						    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Join</a> 
						    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="/project-owner/signup">As Project Owner</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/signup">As Service Provider</a>
								
							</div>
						</li>
						<li class="nav-item"> <a class="nav-link" href="/login">Sign In</a> </li>
					</ul>
				</div>
			</div>
		</nav>
	</header>