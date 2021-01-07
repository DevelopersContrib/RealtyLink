function load_projects(limit, start)
{
 $('#load_data_message').html('<div class="d-flex align-items-center" style="width:100px;"> <strong id="text-message">Loading...</strong><div class="spinner-border ml-auto" role="status" aria-hidden="true"></div></div>');	  
 $('#modal-form-content').html('');
 $('#addproModal').modal('hide');	
 $.ajax({
  url:"/project-owner/dashboard/loadprojects",
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
		  url:"/project-owner/dashboard/loadprojectdiv",
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
	$.ajax({
		  url:"/project-owner/dashboard/loadform",
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
		  url:"/project-owner/dashboard/loadchangestatus",
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
		  url:"/project-owner/dashboard/loadloader",
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

function showformloader(message){
	$('#add-project-loadder').show();
	$('#add-project-loadder #text-message').html(message);
}

function hideformloader(){
	$('#add-project-loadder').hide();
	$('#add-project-loadder #text-message').html('');
}

function showprocesserror(error){
	$('#add-project-error').show();
	$('#add-project-error').html(error);
}

function saveproject(){
	var title = $('#project_title').val();
	var description = $('#project_desc').val();
	var network = $('#project_network').val();
	var image = $('#img_project').attr('src');
	var project_id = $('#project_id').val();
	
	showformloader('Saving project...');
	
	 jQuery.post('/project-owner/dashboard/saveproject',
				{
			      title:title,
			      description:description,
			      network:network,
			      image:image,
			      project_id:project_id
			      
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


function  autoSaveContract(project_id,address,type){
	var network = $('#project_network').val();
	if(typeof network == 'undefined'){
		var network = 'test';
	}
	$.post("/project-owner/crypto/autosavecontract",
 			 {
			 		project_id:project_id,
			 		address:address,
			 		ctype:type,
			 		network:network
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
	
	showformloader('Generating data contract...');
	       
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
                       autoSaveContract(project_id,address,'data');
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
	
	  	showformloader('Generating dan contract...');
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
                  autoSaveContract(project_id,address,'dan');
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
	  showformloader('Generating 1,000,000 SCESH to dan contract');
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
	 jQuery.post('/project-owner/dashboard/deleteproject',
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
	
	showformloader('Saving status...');
	
	 jQuery.post('/project-owner/dashboard/savestatus',
				{
			      status:status,
			      project_id:project_id
			    },
				function(data){
			    	loadprojectdiv(data.project_id);
			});
}
