var $ = jQuery;
jQuery(document).ready(function(){
    jQuery('.dismiss-activation-message,.cc-rate-it .go-to-wordpress-repo').click(function(){
        var message_block = $('.cc-rate-it');
        jQuery.ajax({
            url : admin_params.ajax_url,
            type: 'post',
            data : {
                action : 'dismiss_activation_message',
                value : 'yes'
            },
            success : function(data){
                if(data){
                   message_block.hide() 
                }
            }
        })
    });
});
