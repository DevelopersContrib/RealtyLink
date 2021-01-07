<div class="row">
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <label for=""> Bonded Agent</label>
            <input type="text" class="form-control" id="bond_agent" value="<?if(!empty($bondDetails->row()->bond_agent)) echo $bondDetails->row()->bond_agent?>">
            <strong><small><span class="text-danger bond-error error-bondAgent" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Bond Value</label>
            <input type="text" class="form-control" id="bond_value" value="<?if(!empty($bondDetails->row()->bond_value)) echo $bondDetails->row()->bond_value?>">
            <strong><small><span class="text-danger bond-error error-bondValue" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Bond Info </label>
            <textarea class="form-control" name="" id="bond_info" rows="5"><?if(!empty($bondDetails->row()->info)) echo $bondDetails->row()->info?></textarea>
            <strong><small><span class="text-danger bond-error error-bondInfo" style="display:none;"></span></small></strong>
        </div>
        <div class="form-group mt-3">
            <a href="javascript:;" class="btn btn-secondary" id="btn_save_bond_details"> <i class="fas fa-check" aria-hidden="true"></i> Save </a>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#btn_save_bond_details').on('click',function() {
    let agent = $('#bond_agent').val();
    let value = $('#bond_value').val();
    let info = $('#bond_info').val();

    if(validateBondDetails(agent,value,info)) {
        $.ajax({
            url: '/settings/updatebonddetails',
            method: 'POST',
            data: { agent:agent,value:value,info:info },
            beforeSend: function() {
                $('#btn_save_bond_details').html('<i class="fa fa-spin fa-spinner"></i> Saving...');
            },
            success: function(data) {
                let title = 'Saved'
                let message = 'Bond details has successfully updated';
                let type = 'success';
                
                if(data.status == '0' || data.status == '') {
                    title = 'Error'
                    message = 'An error occured during the saving. Please try again!';
                    type = 'error';
                };

                Swal.fire(
                    title,
                    message,
                    type
                );
            },
            complete: function() {
                $('#btn_save_bond_details').html('<i class="fas fa-check" aria-hidden="true"></i> Save')
            },
        });
    }
});
var validateBondDetails = function(agent,bondValue,info) {
    let value = false;

    $('.bond-error').hide();

    if(!agent) {
        $('.error-bondAgent').html('This field is required..')
            .show();
    } else if(!bondValue) {
        $('.error-bondValue').html('This field is required.')
            .show();
    } else if(!info) {
        $('.error-bondInfo').html('This field is required.')
            .show();
    } else {
        value = true;
    }

    return value;
}
</script>