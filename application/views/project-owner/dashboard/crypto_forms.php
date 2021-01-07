<form method="post" action="javascript:processcontractdata();" class="stdform stdform2" id="formdeploy1" style="display: none">
	      	    	 <p>
                        	<label>Sourcecode</label>
                            <span class="field"><textarea class="data_sourcecode" id="sourcecode" name="sourcecode" ><?php echo file_get_contents($this->config->item('c_data_file'));?></textarea></span>
                            <input id="account" name="account" style="width:400px" type="hidden" value="<?php echo $this->config->item('c_account_owner')?>" /> 
                     </p>
                    
                    <p>
	                 	<label>Contract</label>
	                     <span><select id="contract" name="contract"><option value="<?php echo  $this->config->item('c_data_contract')?>" selected><?php echo  $this->config->item('c_dan_contract')?></option></select></span>
	                 </p> 
	                 
					<p id="params">
					    <?php foreach ($this->config->item('c_data_param') as $param):?>
							<input name="param[]" class="c_data_param" id="c_data_param" placeholder="<?php echo $param?>" value="">
						<?php endforeach;?>
	                 </p>
                      
                      <p>
	                 	<label>Network</label>
	                     <span><input id="c_data_network" name="c_data_network" style="width:400px" type="hidden" value="" /> </span>
	                 </p> 
                  		
                     <p class="stdformbutton">
                        	<button class="submit radius2">Submit</button>
                     </p>
                </form>
                
                
 <form method="post" action="javascript:processcontractdan();" class="stdform stdform2" id="formdeploy2" style="display: none">
	      	    	 <p>
                        	<label>Sourcecode</label>
                            <span class="field"><textarea  class="dan_sourcecode" id="sourcecode" name="sourcecode" ><?php echo file_get_contents($this->config->item('c_dan_file'))?></textarea></span>
                            <input id="account" name="account" style="width:400px" type="hidden" value="<?php echo $this->config->item('c_account_owner')?>" /> 
                     </p>
                    
                    <p>
	                 	<label>Contract</label>
	                     <span><select id="contract" name="contract"><option value="<?php echo $this->config->item('c_dan_contract')?>" selected><?php echo $this->config->item('c_dan_contract')?></option></select></span>
	                 </p> 
	                 
	                 <p>
	                 	<label>Network</label>
	                     <span><input id="c_dan_network" name="c_dan_network" style="width:400px" type="hidden" value="" /> </span>
	                 </p> 
	                 
					<p id="params">
					  
					   
					 		 <input name="param[]" class="c_dan_param" id="c_dan_param" placeholder="<?php echo $this->config->item('c_data_param')[0]?>" value="">
	                          <input name="param[]" class="dataAddressContent" placeholder="<?php echo $this->config->item('c_data_param')[1]?>" value="">
                           
                          
	                 </p>
                      
                  		
                     <p class="stdformbutton">
                        	<button class="submit radius2">Submit</button>
                     </p>
              </form>
              