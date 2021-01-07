function getTasksLatest(page = '1',from=''){
	      var search_key = $('#search_key').val();
	      var sort_by = $('#search_sort').val();
			
				$('.task-loader-loading').show();
				$('#taskcontainer').html(' ');

				$.ajax({
					method: "POST",
					url:  "/taskajax/loadtasks",
					data: { 'page':page,'search_key':search_key,'sort_by':sort_by,'from':from }
				})
				.success(function( data ) {
					$('.task-loader-loading').hide();
					$('#taskcontainer').html(data);
					
				});
			}

function getProjects(){
    		$('#projectscontainer').html(' ');

			$.ajax({
				method: "POST",
				//url:  "/projectajax/loadprojects",
				url: "/projects/loadprojects",
				data: {limit: 7,start: 0 }
			})
			.success(function( data ) {
				$('#projectscontainer').html(data.html);
			});
		}