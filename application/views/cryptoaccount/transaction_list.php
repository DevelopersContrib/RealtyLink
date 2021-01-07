		<div class="col-md-12">
				<h3>Transaction List</h3>
			</div>
			<div class="col-md-12 table-outer">
				<div class="table-box">
					<!-- -->
					<table id="transtable" class="table table-striped table-bordered nowrap" style="width:100%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Transaction ID</th>
								<th>Amount</th>
								<th>Currency</th>
								<th>Notes</th>
								<th>Date</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
																				
						</tbody>
						
					</table>
					<!-- -->
				</div>
			</div>
			<script>
			var transtable = $('#transtable').dataTable({
			    "processing": true,
			    "serverSide": true,
			    "ajax": "/cryptoaccount/translist",
			    "order": [[0, 'ASC']],
			    "columns": [
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="temail text-break text-truncate">'+row[0]+' '+row[1]+'</div>';
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
				            if (row[8]=='test'){
			                  return '<div class="ttid text-break text-truncate"><a href="https://ropsten.etherscan.io/tx/'+row[2]+'" target="_blank">'+row[2]+'</a></div>';
				            }else {
				            	 return '<div class="ttid text-break text-truncate"><a href="https://etherscan.io/tx/'+row[2]+'" target="_blank">'+row[2]+'</a></div>'; 
				            }
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="tamount">'+row[3]+'</div>';
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="tcurrency">'+row[4]+'</div>';
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="tdetails text-break text-truncate"><a href="task/updates/'+row[9]+'" target="_blank">'+row[5]+'</a></div>';
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="tdate">'+row[6]+'</div>';
			            }
			            
			        },
			        {
			            "orderable": true,
			            "searchable": true,
			            "render": (data, type, row) => {
			                return '<div class="tblock">'+row[7]+'</div>';
			            }
			            
			        }
			     
			        
			    ]
			});

			</script>