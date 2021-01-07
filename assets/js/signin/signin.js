  $('#login').click(function(){

       var email = $('#inputEmail').val();

       var password = $('#inputPassword').val();



        if(email == ''){

            $('#email_error').removeClass('d-none');

            $('#email_error').html('Please enter your email!');

        }else if(password == ''){

            $('#password_error').removeClass('d-none');

            $('#password_error').html('Please enter your password!');

        }else{

            $('#password_error').addClass('d-none');

            $('#email_error').addClass('d-none');

            $('#login').html('<span class="loading-icon">&nbsp;<i class="fa fa-spinner fa-spin"></i></span> Processing');

            $.post('/login/signinprocess',{

                email:email,

                password:password

            },function(data){

                if(data.status){
					if(data.redirect!=undefined){
						window.location = data.redirect;
					}else{
						window.location = '/onboarding';
					}
                }else{

                   $('#password_error').removeClass('d-none');

                   $('#password_error').html(data.message);

                   $('#login').html('Log In Now');

                }

            });

        }

    });





