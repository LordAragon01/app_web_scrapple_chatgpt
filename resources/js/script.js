$(function(){

    //Send data for search Content From URL
    $('.searchurl_form').on('submit', function(e){

        e.preventDefault();

        let href = window.location.href;
        let host = window.location.hostname;
        let protocol = window.location.protocol;
        
        let urlvalue = $('#urlsearch').val();
        let default_url = "http://projeto_fox.test:8080/api/searchapi";

        //console.log("Enviado com Sucesso - " + url);
        //console.log(href + '----' + host + '----' + protocol);

        $.ajax({
            type: 'POST',
            url: default_url,
            data: {
                indicateurl: urlvalue
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(){

                console.log("Enviado Por Ajax");

            }, 
            error: function(){

                console.log("Erro ao enviar");

            }

        });


    });


});