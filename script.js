$('#promo-form').on('submit', function(e){
                e.preventDefault();

        var promo_code = $('input[name="promo_code"]').val();
        if(promo_code == ''){
            alert('Field Canot be empty');return false;
        }
        var order_data = JSON.parse(localStorage.getItem('quickqr_order')),
            items = [];
            for (var i in order_data) {
                if (order_data.hasOwnProperty(i)) {
                    items.push(order_data[i]);
                }
            }
            console.log(order_data);            
            var action = $(this).data('ajax-action'),
            data = { action: action, promo_code: promo_code, items: JSON.stringify(items) };

            $.ajax({
                type: "POST",
                url: ajaxurl+'?action='+action,
                data: data,
                dataType: 'json',
                success: function (response) {
                    alert(response);
                    // if(response.success){
                    //     // clear order data
                    //     localStorage.setItem('quickqr_order','{}');
                    //     $('#view-order-wrapper').hide();
                    //     //$form.find('input').val('');

                    //     if(response.message != '' && response.message != null){
                    //         location.href = response.message;
                    //     }else{
                    //         $('.your-order-content').slideUp();
                    //         $('.order-success-message').slideDown();

                    //         if(response.whatsapp_url != '' && response.whatsapp_url != null) {
                    //             // send to whatsapp
                    //             location.href = response.whatsapp_url;
                    //         }
                    //     }

                    // }else{
                    //     $form.find('.form-error').html(response.message).slideDown();
                    // }
                }
            });

    })