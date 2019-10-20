jQuery(document).ready(function($){'use strict';




    // Dynamic Modal
    function wpneo_crowdfunding_modal(data){
        var html;
        html = '<div class="thm-wishlist-popup">';
        html += '<div class="thm-wishlist-inner">';
                html += '<a href="#" class="thm-wishlist-close">x</a>';
                if( data.image ){ html += '<img src="'+data.image+'">'; }
                if( data.title ){ html += '<h1>'+data.title+'</h1>'; }
                if( data.message ){ html += '<div class="message-body">'+data.message+'</div>'; }
                if( data.btnText && data.btnUrl ){ html += '<a href="'+data.btnUrl+'" class="btn round-btn">'+data.btnText+'</a>'; }
            html += '</div>';
        html += '</div>';

        if ($('.thm-wishlist-popup').length == 0){
            $('body').append(html);
        }
        $('.thm-wishlist-popup').addClass('active');
    }

    $('.thm-wishlist-close').live('click',function(e){
        $('.thm-wishlist-popup').removeClass('active');
    });





    // Set Cookie After Click Button
    $('.add-to-wishlist').on('click',function(e){
        e.preventDefault();
        var productid = $(this).data('productid');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_.ajaxurl,
            data: { 'action': 'setwishlist','productid': productid },
            success: function(data){
                wpneo_crowdfunding_modal( data );
            }
        });
    });

    // Wishlist to Add to Cart
    $('.wishlist-to-cart').click(function(e) {
        e.preventDefault();
        var productid = $(this).data('productid');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_.ajaxurl,
            data: { 'action': 'setcartlist','productid': productid },
            success: function(data){
                wpneo_crowdfunding_modal( data );
            }
        });
     });

     // Remove Wishlist
     $('.wishlist-remove').click(function(e) {
        e.preventDefault();
        var productid = $(this).data('productid');
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: ajax_.ajaxurl,
            data: { 'action': 'removecart','productid': productid },
            success: function(data){
                wpneo_crowdfunding_modal( data );
            }
        });
     });


});
