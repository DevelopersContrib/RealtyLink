<?php  echo '<?xml version="1.0" encoding="' . $encoding . '"?>' . "\n"; ?>  
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:admin="http://webns.net/mvcb/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:content="http://purl.org/rss/1.0/modules/content/">  
  <channel>  
  <title><?php echo $page_description; ?></title> 
   <link><?php echo $feed_url; ?></link>  
   <description><?php echo $page_description; ?></description>  
   <dc:language><?php echo $page_language; ?></dc:language>  
   <dc:creator><?php echo $creator_email; ?></dc:creator>  
   <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>  
   <?php if ($updates->num_rows() > 0):?>	 
		<?php foreach($updates->result() as $update):?>
		   <item>  
			<title><?php echo $update->firstname.' '.$update->lastname?></title>
		   <link><?php echo $this->config->item('main_url')?><?php echo $update->link; ?></link>
			 <?php $message = $update->firstname.' '.$update->lastname.' '." $update->message";?>
			   <description><?php echo character_limiter(strip_tags(htmlspecialchars($message , ENT_QUOTES, 'UTF-8')), 200); ?></description>  
			   <pubDate><?php echo date("r", strtotime($update->date_created)); ?></pubDate>
				<guid><?php echo $this->config->item('main_url')?><?php echo $update->link; ?></guid>
		   </item>  
	   <?php endforeach; ?>  
   <?php endif?>
   </channel>  
</rss>  