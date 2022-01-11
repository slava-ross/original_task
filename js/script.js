$(document).ready(function(){

    window.setInterval(function () {
        $(".flash").fadeTo(500, 0).slideUp(500, function () {
            $(this).remove();
        });
    }, 8000);

    function loaderOn() {
        $('.loader-icon').removeClass('shrinking-cog').addClass('spinning-cog');
        $('#loader').show();
    }

    function loaderOff() {
        setTimeout(() => {
                $('.loader-icon').removeClass('spinning-cog').addClass('shrinking-cog');
                setTimeout(() => {
                        $('#loader').hide();
                    },200
                );
            },100
        );
    }

    $('#book-search').on('click', function (e) {
        e.preventDefault();
        const token = $('input[name="_token"]').attr('value');
        const formData = $('#book-search-form').serialize();
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-Token': token
            },
            dataType: 'json',
            url: 'ajax.php',
            data: formData,
            beforeSend: loaderOn(),
            success: function (response) {
                if(response.success === true) {
                    $('#book-searching-result').show();
                    $('#table-rows').html(response.html);
                } else {
                    if(!$('.container-main').children('.alert').length) {
                        $('.container-main').prepend(response.html);
                    }
                }
            },
            complete: loaderOff(),
            error: function(jqXHR, textStatus, errorThrown) {
                loaderOff();
                console.log(JSON.stringify(jqXHR));
                console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                console.warn(jqXHR.responseText);
            }
        });
    });

    $('.btn-del').on('click', function(e) {
        let result = confirm("Подтверждаете удаление?");
        if (!result) {
            e.preventDefault();
        }
    });

});
