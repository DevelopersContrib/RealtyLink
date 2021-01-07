function load_projects(limit, start)
{
 $('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
 $('#modal-form-content').html('');
 $('#addproModal').modal('hide');	
 $.ajax({
  url:"/project-owner/myprojects/loadprojects",
  method:"POST",
  data:{limit:limit, start:start},
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

function loadprojectdiv(id){
	$('#modal-form-content').html('');
	$('#addproModal').modal('hide');	
	$.ajax({
		  url:"/project-owner/myprojects/loadprojectdiv",
		  method:"POST",
		  data:{id:id},
		  cache:false,
		  success:function(data)
		  {
		    $('#project-content-div'+id).html(data.html);
		  }
		 });
}
function loadform(id){
	if (id!=0){
		$('.modal-title').html('Edit Project');
	}
	$.ajax({
		  url:"/project-owner/myprojects/loadform",
		  method:"POST",
		  data:{id:id},
		  cache:false,
		  success:function(data)
		  {
		   $('#modal-form-content').html(data);
			$('#addproModal').modal('show');
		  }
		 });
}

function loadchangestatus(id){
	$.ajax({
		  url:"/project-owner/myprojects/loadchangestatus",
		  method:"POST",
		  data:{id:id},
		  cache:false,
		  success:function(data)
		  {
		   $('#modal-form-content').html(data);
			$('#addproModal').modal('show');
		  }
		 });
}

function loadloader(id,message=''){
	$.ajax({
		  url:"/project-owner/myprojects/loadloader",
		  method:"POST",
		  data:{id:id,message:message},
		  cache:false,
		  success:function(data)
		  {
		   $('#modal-form-content').html(data);
			$('#addproModal').modal('show');
		  }
		 });
}

function showformloader(id){
	$('.addprojectform').hide();
	$('.progress-container').show();
	
	$('#load_save_project').hide();
	$('#load_save_data').hide();
	$('#load_save_dan').hide();
	$('#load_save_esh').hide();
	
	
	switch(id){
		case 'project':
			$('#load_save_project').show();
			$('#load_save_project .progress-title').show();
			$('#load_save_project .progress-animation').show();
			
		break;
		case 'data':
			$('#load_save_project').show();
			$('#load_save_project .progress-title').hide();
			$('#load_save_project .progress-animation').hide();
			$('#load_save_project .progress-title-completed').show();
			
			$('#load_save_data').show();
			$('#load_save_data .progress-title').show();
			$('#load_save_data .progress-title-completed').hide();
			$('#load_save_data .progress-animation').show();
			
		break;
		case 'dan':
			
			$('#load_save_project').show();
			$('#load_save_project .progress-title').hide();
			$('#load_save_project .progress-animation').hide();
			$('#load_save_project .progress-title-completed').show();
			
			$('#load_save_data').show();
			$('#load_save_data .progress-title').hide();
			$('#load_save_data .progress-animation').hide();
			$('#load_save_data .progress-title-completed').show();
			
			$('#load_save_dan').show();
			$('#load_save_dan .progress-title').show();
			$('#load_save_dan .progress-title-completed').hide();
			$('#load_save_dan .progress-animation').show();
			
		break;
		case 'esh':
			
			$('#load_save_project').show();
			$('#load_save_project .progress-title').hide();
			$('#load_save_project .progress-animation').hide();
			$('#load_save_project .progress-title-completed').show();
			
			$('#load_save_data').show();
			$('#load_save_data .progress-title').hide();
			$('#load_save_data .progress-animation').hide();
			$('#load_save_data .progress-title-completed').show();
			
			
			$('#load_save_dan').show();
			$('#load_save_dan .progress-title').hide();
			$('#load_save_dan .progress-animation').hide();
			$('#load_save_dan .progress-title-completed').show();
			
			$('#load_save_esh').show();
			$('#load_save_esh .progress-title').show();
			$('#load_save_esh .progress-title-completed').hide();
			$('#load_save_esh .progress-animation').show();
			
		break;
		case 'status':
			$('#load_save_status').show();
			$('#load_save_status .progress-title').show();
		break;
		
	}
}

function hideformloader(){
	$('.progress-container').hide();
	$('#add-project-loadder').hide();
	$('#add-project-loadder #text-message').html('');
}

function showprocesserror(error){
	$('.addprojectform').show();
	$('.progress-container').hide();
	$('#add-project-error').show();
	$('#add-project-error').html(error);
}

function saveproject(){
	var title = $('#project_title').val();
	var description = $('#project_desc').val();
	var network = $('#project_network').val();
	var image = $('#img_project').attr('src');
	var project_id = $('#project_id').val();
	var image_cover = $('#img_project_cover').attr('src');
	var country_id = $('#project_country').val();
	var phone_number = $('#project_phone_number').val();
	var state = $('#project_state').val();
	var city = $('#project_city').val();
	var address = $('#project_address').val();
	var zipcode = $('#project_zipcode').val();
	
	showformloader('project');
	
	 jQuery.post('/project-owner/myprojects/saveproject',
				{
			      title:title,
			      description:description,
			      network:network,
			      image:image,
			      image_cover:image_cover,
			      project_id:project_id,
			      country_id:country_id,
			      phone_number:phone_number,
			      state:state,
			      city:city,
			      address:address,
			      zipcode:zipcode
			    },
				function(data){
			    	var project_id = data.project_id;
			    	if (data.update){
			    		loadprojectdiv(data.project_id);
			    	}else {
			  	     processcontractdata(project_id);
			    	 /*$('#load_data').html('');	
			    	  load_projects(5, 0);
			    	  */
			    	}
			});
	
}


function  autoSaveContract(project_id,address,type,hash){
	var network = $('#project_network').val();
	if(typeof network == 'undefined'){
		var network = 'test';
	}
	$.post("/project-owner/crypto/autosavecontract",
 			 {
			 		project_id:project_id,
			 		address:address,
			 		ctype:type,
			 		network:network,
					hash:hash
 			 }
 			 ,function(data){
 			 }
 	 );
}

function updateMint(id){
	$.post("/project-owner/crypto/updatemint",
			 {
			 		id:id
			 }
			 ,function(data){
			 }
	 );
}

function processcontractdata(project_id){
	var title = $('#project_title').val();
	if(typeof title == 'undefined'){
		var title = $('.btncreatedata'+project_id).attr('data');
	}
	var network = $('#project_network').val();
	if(typeof network == 'undefined'){
		var network = 'test';
	}
	var c_name = title.replace(" ", "");
	c_name = c_name.substring(1, 8);
	$('#c_data_param').attr('value', c_name+' data');
	$('#c_dan_param').attr('value', c_name+' dan');
	$('#c_data_network').attr('value', network);
	$('#c_dan_network').attr('value', network);
	
	showformloader('data');
	       
	  	   $.post("/project-owner/crypto/godeploy",  $("#formdeploy1").serialize(),function(data){
	  		    if ((data.Hash != '')&&(data.Hash != null)){
	  		    	var address ='';
            var hash = data.Hash;
            var network = data.network;
            var interval = setInterval(function(){
               $.post("/project-owner/crypto/ajaxfetchaddress",
        			 {
      			 		hash:hash,
      			 		network:network
        			 }
        			 ,function(data){
        				 address = data.address;
        			 }
        	    );	
              
              console.log(address);
              if ((address !='') && (address != undefined)){
                 clearTimeout(interval);
                 $('.dataAddressContent').attr('value', address);
                       autoSaveContract(project_id,address,'data',hash);
      					  setTimeout(function(){
      						processcontractdan(project_id);
      						}, 5000);
              }
            },5000);	
            
  					
	  		    }else {
	  		    	hideformloader();
             if (data.message != ''){
               showprocesserror(data.message);
             }else {
            	 showprocesserror('No data address generated. You can generate data contract under Project list');
	  		  
            }
	  		    }
			});
	  
}

function processcontractdan(project_id){
	var title = $('#project_title').val();
	if(typeof title == 'undefined'){
		var title = $('.btncreatedan'+project_id).attr('data');
	}
	var network = $('#project_network').val();
	if(typeof network == 'undefined'){
		var network = 'test';
	}
	var c_name = title.replace(" ", "");
	c_name = c_name.substring(1, 8);
	$('#c_data_param').attr('value', c_name+' data');
	$('#c_dan_param').attr('value', c_name+' dan');
	$('#c_data_network').attr('value', network);
	$('#c_dan_network').attr('value', network);
	
	  	showformloader('dan');
		   $.post("/project-owner/crypto/godeploy",  jQuery("#formdeploy2").serialize(),function(data){
			   if ((data.Hash != '')&&(data.Hash != null)){
		 		   var address ='';
                   var hash =  data.Hash ;
                   var network = data.network;
            var interval = setInterval(function(){
              $.post("/project-owner/crypto/ajaxfetchaddress",
        			 {
      			 		hash:hash,
      			 		network:network
        			 }
        			 ,function(data){
        				 address = data.address;
        			 }
        	    );	
              
              console.log(address);
              if ((address !='') && (address != undefined)){
                 clearTimeout(interval);
                  autoSaveContract(project_id,address,'dan',hash);
        		 			console.log(data.Hash +" - Dan Address - "+ address);
        		 			setTimeout(function(){
        		 				processeshtransfer(project_id);
        						}, 5000);
              }
            },5000);	
 		   }else {
 				hideformloader();
             if (data.message != ''){
            	 showprocesserror(data.message);
             }else {
	  		    	 showprocesserror('No dan address generated. You can generate dan contract under Project list');
               }
			   }
	 			
	 		});
	  
}

function processeshtransfer(project_id){
	  showformloader('esh');
	  jQuery.post("/project-owner/crypto/minteshdao",
	  			 {
				 		project_id:project_id
				 		
	  			 }
	  			 ,function(data){
	  				 
	  			   if ((data.Hash != '')&&(data.Hash != null)){
			 	       var hash =  data.Hash ;
			 	       var success = false;
			 	       var mint_id = data.mint_id;
	                   var network = data.network;
			            var interval = setInterval(function(){
			              $.post("/project-owner/crypto/ajaxfetchaddress",
			        			 {
			      			 		hash:hash,
			      			 		network:network
			        			 }
			        			 ,function(data){
			        				 success = data.status;
			        			 }
			        	    );	
			              
			              if (success){
			            	  clearTimeout(interval);
			                  updateMint(mint_id);
			              	  setTimeout(function(){
			              		  $('#modal-form-content').html('');
				            	  $('#addproModal').modal('hide');	
				                 $('#load_data').html('');	
						    	 load_projects(5, 0);
						 		}, 5000);
			        		 			
			              }
			            },5000);	
	            
	 		   }else {
	 				hideformloader();
	             if (data.message != ''){
	            	 showprocesserror(data.message);
	             }else {
	            	 showprocesserror('An error occurred while generating  SCESH. You can send SCESH under Project List');
	               }
				   }
	  				 
	  				 
	  				 
	  			 }
	  	 );	
}


function processProject(){
	var title = $('#project_title').val();
	var description = $('#project_desc').val();
	var network = $('#project_network').val();
	var image = $('#img_project').attr('src');
	var image_cover = $('#img_project_cover').attr('src');
	
	if (title == ""){
		$('#project_title').focus();
		$('#project_title').siblings('.txt-alert')
		.html('Please enter title!')
			.show();
	}else if (description == ""){
		$('#project_desc').focus();
		$('#project_desc').siblings('.txt-alert')
		.html('Please enter description!')
			.show();
	}else {
		$('.addprojectform').fadeOut();
		var c_name = title.replace(" ", "");
		c_name = c_name.substring(1, 8);
		$('#c_data_param').attr('value', c_name+' data');
		$('#c_dan_param').attr('value', c_name+' dan');
		$('#c_data_network').attr('value', network);
		$('#c_dan_network').attr('value', network);
		//showformloader('Generating data contract...');
		saveproject();
	}
}

function deleteproject(id){
	 jQuery.post('/project-owner/myprojects/deleteproject',
				{
			      project_id:id
			    },
				function(data){
			    	$('#project-content-div'+data.project_id).fadeOut();
			});
}

function processStatus(){
	var status = $('#project_status').val();
	var project_id = $('#project_id').val();
	
	showformloader('status');
	
	 jQuery.post('/project-owner/myprojects/savestatus',
				{
			      status:status,
			      project_id:project_id
			    },
				function(data){
			    	loadprojectdiv(data.project_id);
			});
}
