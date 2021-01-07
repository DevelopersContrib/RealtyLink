<?php $this->load->view('project-owner/dashboard/header');?>
<?php $this->load->view('project-owner/dashboard/navigation');?>
<link rel="stylesheet" href="https://www.servicechain.com/assets/css/star.css">
<div class="container py-5">
	
	<div class="row justify-content-center">
		<?php $this->load->view('project-owner/kanban/rating',['assign_to_name'=>'','task_title'=>'',]);?>
		
		
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label for=""> Comment </label>
				<textarea class="form-control" name="" id="bond_info" rows="5"></textarea>
			</div>
		</div>
	</div>
</div>
<script>

$( document ).ready(function() {
	
	Swal.fire({
  title: '<strong>HTML <u>example</u></strong>',
  icon: 'info',
  html:
    'You can use <b>bold text</b>, ' +
    '<a href="//sweetalert2.github.io">links</a> ' +
    'and other HTML tags',
  showCloseButton: true,
  showCancelButton: true,
  focusConfirm: false,
  confirmButtonText:
    '<i class="fa fa-thumbs-up"></i> Great!',
  confirmButtonAriaLabel: 'Thumbs up, great!',
  cancelButtonText:
    '<i class="fa fa-thumbs-down"></i>',
  cancelButtonAriaLabel: 'Thumbs down'
}).then(result => {
		if (result.value) {
			alert('yes');
		} else {
			alert('no');
		}
	});

	// Swal.fire({
	// title: '<strong>HTML <u>example</u></strong>',
	// showConfirmButton: true,
	// onBeforeOpen: () => {
		// console.log('test');
	  // },
	// html:
	// 'insert star rating here'
	// }).then(result => {
		// if (result.value) {
			// alert('yes');
		// } else {
			// alert('no');
		// }
	// });
	
	// Swal.fire({title: 'Are you sure?', showCancelButton: true}).then(result => {
	  // if (result.value) {debugger;
		
	  // } else {debugger;
		
	  // }
	// })
});
</script>
<?php $this->load->view('project-owner/dashboard/footer');?>
