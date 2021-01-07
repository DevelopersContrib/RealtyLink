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

<ul class="pagination justify-content-end">
	<?if($start > 1):?><li class="page-item"><a href="javascript:previous(<?=($start - $page_display_per_slide)?>)">&laquo;</a></li><?endif;?>

	<?for($page = $start; $page <= $last_page && $page <= $last_page_this_slide ; $page++):?>
	<li <?echo $page == $current_page ? 'class="page-item active"':'page-item'?> ><a class="page-link" href="javascript:;" onclick="loadpaginate(<?=$page?>,'<?=$container?>')" ><?=$page?></a></li>
	<?endfor;?>
	<?if($shownext):?><li class="page-item"><a class="page-link" href="javascript:next(<?=($last_page_this_slide+1)?>)">&raquo;</a></li><?endif;?>

</ul>
		
<?endif;?>
