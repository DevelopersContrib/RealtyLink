<?php if ($projects->num_rows() > 0): ?>
<div class="sec-heading col-md-12 text-center mb-5">
						<h2>Latest Projects</h2>
					</div>
	<?php foreach ($projects->result() as $row): ?>
	<div class="col-lg-4">
						<p class="conlist"> <i class="fa fa-star-o"></i>&nbsp; 
						<a href="/project/details/<?php echo $row->id?>/<?php echo $row->slug?>" target="_blank">
						<?php echo $row->title?>                                                
                         </a> 
						</p>
					</div>
	<?php endforeach;?>
<?php endif?>