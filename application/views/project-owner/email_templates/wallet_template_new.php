<html>
	<head>
		<title>Servicechain</title>
	</head>
	<body width="100%" style="margin: 20px 0 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #fafafa;">

		<div style="max-width: 600px; margin: 0 auto; background: #fafafa; border: 1px solid #eaeaea; border-radius: 3px;" class="email-container">
			<!-- BEGIN BODY -->
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">
				<tr>
					<td valign="top" class="bg_white" style="padding: 1em 2.5em;">
						<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td width="40%" class="logo" style="text-align: center;">
									<h1 style="margin-bottom: 0px;">
										<a href="<?php echo $this->config->item('main_url')?>">
											<img src="<?php echo $this->config->item('site_logo')?>" height="50" />
										</a>
									</h1> 
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- end tr -->
				<tr>
					<td valign="middle" class="hero bg_white" style="background-image: url(); background-color: #ffffff; background-size: cover; height: 400px;">
						<div class="overlay" style="position: absolute;top: 0;left: 0;right: 0;bottom: 0; width: 100%;background: #000000;z-index: -1;opacity: .7;"></div>
						
						<table>
							<tr>
								<td>
									<div class="text" style="padding: 0 3em; text-align: center; color: #333333;font-size:18px">
										<h1>Hello <?php echo $name?>,</h1>
										<?php echo $message?>
									</div>
								</td>
							</tr>
						</table>
						
					</td>
				</tr>
				<!-- end tr -->
				<!-- 1 Column Text + Button : END -->
			</table>
			<table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: auto;">				
				<tr>
					<td valign="middle" class="bg_black footer email-section" style="padding: 2.5em; background: #555555;">
						<table>
							<tr>
								<td valign="top" width="33.333%">
									<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
										<tr>
											<td style="text-align: left; padding-right: 10px; color: #ffffff;">
												<p>Copyright <?=date('Y')?> Servicechain.com. All Rights Reserved</p>
											</td>
										</tr>
									</table>
								</td>
								<td valign="top" width="33.333%">
									<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
										<tr>
											<td style="text-align: right; padding-left: 5px; padding-right: 5px;">
												<p> <a href="#" style="color: rgba(255,255,255,.4);">Unsubcribe</a> </p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		
	</body>
</html>