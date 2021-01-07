function load_projects(limit, start)
{
 var search_key = $('#search_key').val();
 var status = $('#filter_status').val();
 $('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
 $.ajax({
  url:"/projects/loadprojects",
  method:"POST",
  data:{limit:limit, start:start, search_key:search_key, status:status},
  cache:false,
  success:function(data)
  {
   $('#load_data').append(data.html);
   if(data.html == '')
   {
   	$('#load_data_message').css('display','none');
    $('#load_data_message').html('');
    action = 'active';
   }
   else
   {
	$('#no-project-error').hide();   
   	$('#load_data_message').css('display','');
	$('#load_data_message').html('');
    action = "inactive";
   }
  }
 });
}