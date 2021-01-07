<div class="row">
    <div class="col-md-12">
        <hr>
        <div class="form-group">
            <label for=""> Facebook</label>
            <input type="text" class="form-control socials" id="facebook_url" value="<?if(!empty($socials->row()->facebook_url)) echo $socials->row()->facebook_url?>">
            <strong><small><span class="text-danger socials-error error-facebook" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Twitter</label>
            <input type="text" class="form-control socials" id="twitter_url" value="<?if(!empty($socials->row()->twitter_url)) echo $socials->row()->twitter_url?>">
            <strong><small><span class="text-danger socials-error error-twitter" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Instagram</label>
            <input type="text" class="form-control socials" id="instagram_url" value="<?if(!empty($socials->row()->instagram_url)) echo $socials->row()->instagram_url?>">
            <strong><small><span class="text-danger socials-error error-instagram" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Github</label>
            <input type="text" class="form-control socials" id="github_url" value="<?if(!empty($socials->row()->github_url)) echo $socials->row()->github_url?>">
            <strong><small><span class="text-danger socials-error error-github" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Skype Username</label>
            <input type="text" class="form-control socials" id="skype" value="<?if(!empty($socials->row()->skype_username)) echo $socials->row()->skype_username?>">
            <strong><small><span class="text-danger socials-error error-skype" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group">
            <label for=""> Telegram</label>
            <input type="text" class="form-control socials" id="telegram" value="<?if(!empty($socials->row()->telegram)) echo $socials->row()->telegram?>">
            <strong><small><span class="text-danger socials-error error-telegram" style="display:none;"></span></small></strong> 
        </div>
        <div class="form-group mt-3">
            <a href="javascript:;" class="btn btn-secondary" id="btn_save_socials"> <i class="fas fa-check" aria-hidden="true"></i> Save </a>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#btn_save_socials').on('click',function() {
    let fb = $('#facebook_url').val();
    let twitter = $('#twitter_url').val();
    let ig = $('#instagram_url').val();
    let github = $('#github_url').val();
    let skype = $('#skype').val();
    let telegram = $('#telegram').val();
    let status = true;
    $('.socials-error').hide();

    if(fb) {
        let regex = /(?:(?:http|https):\/\/)?(?:www.)?facebook.com\/(?:(?:\w)*#!\/)?(?:pages\/)?(?:[?\w\-]*\/)?(?:profile.php\?id=(?=\d.*))?([\w\-]*)?/;

        if(!regex.test(fb)) {
            $('.error-facebook').html('Please enter a valid facebook profile url.')
                .show();
            status = false;
        }
    }
    if(twitter) {
        let regex = /http(?:s)?:\/\/(?:www\.)?twitter\.com\/([a-zA-Z0-9_]+)/;

        if(!regex.test(twitter)) {
            $('.error-twitter').html('Please enter a valid twitter url.')
                .show();
            status = false;
        }
    }
    if(ig && !validateUrl(ig) && ig.indexOf('instagram.com') > 0) {
        $('.error-instagram').html('Please enter a valid instagram url.')
            .show();
        status = false;
    }
    if(github && !validateUrl(github) && github.indexOf('github.com') > 0) {
        $('.error-github').html('Please enter a valid github url.')
            .show();
        status = false;
    }
    if(skype && !validateUsername(skype)) {
        $('.error-skype').html('Please enter a valid skype username.')
            .show();
        status = false;
    }
    if(telegram && !validateUsername(telegram)) {
        $('.error-telegram').html('Please enter a valid telegram username.')
            .show();
        status = false;
    }

    let len = $("input.socials").filter(function () {
                return $.trim($(this).val()).length == 0
              }).length;

    if(len !== 6 && status === true) {
        $.ajax({
            url: '/settings/savesocials',
            method: 'POST',
            data: { facebook_url:fb,twitter_url:twitter,instagram_url:ig,github_url:github,skype:skype,telegram:telegram },
            beforeSend: function() {
                $('#btn_save_socials').html('<i class="fa fa-spin fa-spinner"></i> Saving...');
            },
            success: function(data) {
                let title = 'Saved'
                let message = 'Socials has successfully updated';
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
                $('#btn_save_socials').html('Save');
            },
        });
    }
});

var validateUrl = function(url) {
    let regex = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;

    return regex.test(url);
}
var validateUsername = function(username) {
    let regex = /^[a-z0-9_.]{3,16}$/;

    return regex.test(username);
}
</script>