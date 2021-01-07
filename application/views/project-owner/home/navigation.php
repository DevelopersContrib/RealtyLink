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
						<li class="nav-item active"> <a class="nav-link" href="/project-owner">Home</a> </li>
						<li class="nav-item"> <a class="nav-link" href="/project-owner/signup">Join</a> </li>
						<li class="nav-item"> <a class="nav-link" href="/project-owner/login">Sign In</a> </li>
					</ul>
				</div>
			</div>
		</nav>
	</header>