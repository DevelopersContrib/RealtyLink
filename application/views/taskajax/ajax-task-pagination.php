<?if($pages_count > 1):

$page_display_per_slide = 10;
$last_page = $pages_count;

//get start of pagination
if($current_page < $page_display_per_slide){
    $start = 1;
}else{
    $start = ($current_page/$page_display_per_slide) * $page_display_per_slide;
}

//show next button?
$last_page_this_slide = ($start + $page_display_per_slide) - 1 ;
if($last_page_this_slide < $pages_count){
    $shownext = true;
}else{
    $shownext = false;
}

?>
<div class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<nav >
				<ul class="pagination">
					<?if($start > 1):?><li class="page-item"><a class="page-link" href="javascript:getTasksLatest(<?=($start - $page_display_per_slide)?>,'<?php echo $from?>')">&laquo;</a></li><?endif;?>

					<?for($page = $start; $page <= $last_page && $page <= $last_page_this_slide ; $page++):?>
					<li class="page-item" <?echo $page == $current_page ? 'class="active"':''?> ><a class="page-link" href="javascript:getTasksLatest(<?=$page?>,'<?php echo $from?>')"><?=$page?></a></li>
					<?endfor;?>
					<?if($shownext):?><li class="page-item"><a class="page-link" href="javascript:getTasksLatest(<?=($last_page_this_slide+1)?>,'<?php echo $from?>')">&raquo;</a></li><?endif;?>

				</ul>
			</nav>
		</div>
	</div>
</div>

<?endif;?>
