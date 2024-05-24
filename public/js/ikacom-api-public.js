jQuery(document).ready(function ($) {

    $('.icon-container').on('click', function(e) {
        // Check if the Clipboard API is supported in the browser
        if (navigator.clipboard) {
          navigator.clipboard.readText()
            .then(clipboardText => {
              // Paste the clipboard content into the input field
                $('.access-code-input').val(clipboardText);
            })
            .catch(error => {
              console.error('Failed to read clipboard contents: ', error);
            });
        } else {
          // If Clipboard API is not supported, fallback to the traditional method
          $('.access-code-input').focus();
          document.execCommand('paste');
        }
      });

    $('#ikacom-access-code-validation').on('click', function (e) {
        e.preventDefault();
        var input = $('.access-code-input');
        var access_code = input.val();
        var sva = input.data('sva');
        var code_formule = input.data('code_formule');
        var wallet_bal = input.data('wallet_bal');
        var tarif = input.data('tarif');
        var validate = $(this);
        var notice = $('.validation-block .result-message')

        // Trigger the animation by adding the 'animate' class
        validate.addClass('animate');

        if(access_code ) {

            $.ajax({
                url: ikacom_api_data.ajaxurl,
                data: {
                    action: 'validate_ikacom_access_code',
                    security: ikacom_api_data.nonce,
                    payload: 'ikacom_post_request',
                    access_code: access_code,
                    sva: sva,
                    code_formule: code_formule,
                    wallet_bal: wallet_bal,
                    tarif: tarif
                },
                type: 'post',
                dataType: 'json',
                success: function (result, textstatus) {
                    if (result) {
                        // handle successfull api connection
                        var response = result.data.response_body;
                        if (200 === result.data.response_code && response.statut === "true") {
                            //code valide
                            if (response.etat_code == '1') {
                                notice.html(result.data.wallet_solde_updated)
                                input.attr('data-wallet_bal', result.data.new_solde) 
                                validate.removeClass('animate').addClass('success disabled');
                                validate.attr('disabled')
                                setTimeout(function () {
                                    input.val('');
                                    validate.removeClass('success disabled');
                                    notice.text('');
                                }, 7000); 
                            } else {
                                //console.log(result.data)
                                notice.text(response.details)
                                validate.removeClass('animate').addClass('error');
                                setTimeout(function(){
                                    validate.removeClass('error');                               
                                }, 5000);                    
                                setTimeout(function(){
                                    notice.text('')                               
                                }, 8000);        
                            }    
                            
                        }else{
                            notice.text(response.details)
                            validate.removeClass('animate').addClass('error');
                            setTimeout(function(){
                                validate.removeClass('error');
                            }, 5000);                    
                            setTimeout(function(){
                                notice.text('')
                            }, 8000);                    
                        }

                    }
                },
                error: function (result) {
                    //console.log(result);
                    //console.log('fail');
                    notice.text(result.responseText);
                    validate.removeClass('animate').addClass('error');
                    setTimeout(function(){
                        validate.removeClass('error');
                    }, 5000);                    
                    setTimeout(function(){
                        notice.text('')
                    }, 8000);   
                },
            });
        } else {
            notice.text('code d\'accès vide')
            validate.removeClass('animate').addClass('error');
            setTimeout(function () {
                validate.removeClass('error');
                notice.text('');
            }, 2000);
        }
    });
});
