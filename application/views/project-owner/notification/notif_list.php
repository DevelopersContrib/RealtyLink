<?php  if ($query->num_rows() > 0):?>
	<?php  foreach ($query->result() as $row):?>
		<div class="notibox">
										<div class="media">
											<div class="media-body">
												<h6 class="noti-title mt-0">
													<i class="far fa-bell"></i>
													<a href="<?php echo $row->link?>"><?php echo $row->subject?></a>
												</h6>
												<div class="noti-desc mb-2">
													<?php echo $row->message?>
												</div>
												<div class="notif">
												    <?php if ($row->from_id != null):?>
													<div class="notifrom">
													  <?php if ($this->membersdata->getinfo('firstname','id',$row->from_id) != null):?>
													  From:&nbsp;<?php echo $this->membersdata->getinfo('firstname','id',$row->from_id).' '.$this->membersdata->getinfo('lastname','id',$row->from_id)?>
													  <?php else:?>
													  From:&nbsp;<?php echo $this->membersdata->getinfo('username','id',$row->from_id)?>
													  <?php endif?>
													</div>
													<?php endif?>
													<div class="notidate">
														<?php echo date( 'm/d/Y', strtotime( $row->date_created ) );?>
													</div>
												</div>
											</div>
										</div>
			</div>
	<?php endforeach;?>									
<?php endif?>									