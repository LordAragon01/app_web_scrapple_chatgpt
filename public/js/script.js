let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;
let default_url = "http://projeto_fox.test:8080/api/searchapi";

//Count Id Row
let count = 0;

//List of Sites for search
const listOfSites = ["staples", "worten", "amazon"];

let responseData;

$(function(){

    //Send data for search Content From URL
    $('.searchurl_form').on('submit', function(e){

        e.preventDefault();

        count++;
        
        //Get Data from input
        let urlvalue = $('#urlsearch').val();

        //Get Site Name from Input
        let getNameFromUrl = getSiteName(urlvalue);
        let capitalizeSiteName = getNameFromUrl.charAt(0).toUpperCase() + getNameFromUrl.slice(1);

        //console.log(capitalizeSiteName);

        //let getUrlContent = searchUrl(default_url, urlvalue);

        $.ajax({
            type: 'POST',
            url: default_url,
            data: {
                indicateurl: urlvalue
            },
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function(data, status, xhr){

                if(status === 'success'){

                    //console.log(xhr);

                    //Get Content when the request is success
                    let trcontent = '<tr>';
                            trcontent += '<th scope="row">'+ count +'</th>';
                            trcontent += '<td>' + capitalizeSiteName + '</td>';  
                            trcontent += '<td>' + data.title + '</td>';
                            trcontent += '<td>$ ' + data.price + '</td>';
                            trcontent += '<td>' + data.total_reviews + '</td>';
                            trcontent += '<td>' + data.total_stars + '</td>';
                        trcontent += '</tr>';

                    //Append Content
                    $(trcontent).appendTo($('table tbody'));    

                }

            }, 
            error: function(jqXhr, textStatus, errorMessage){

                console.log("Erro ao enviar");

            }

        });


    }).delay(400);


});

/*---Get Site Name----*/

function getSiteName(urlvalue){

    //Get Name
    let filterNameSite;

    //Split URL
    let getSite = urlvalue.split("/");

    //Fiter url and return sitename
    getSite.filter((value) => {

        if(value.includes('www')){

            let getNameFromUrl = value.split(".");

            filterNameSite =  getNameFromUrl.filter((valuesecond) => {
        
                if(listOfSites.includes(valuesecond)){
        
                    return valuesecond;
        
                }
        
            });

        }

    });

    return filterNameSite.toString();

}

/*---SEARCH DATA FUNCTION---*/

/* async function searchUrl(url, urldata){

    try{

        let response = await fetch(url, {  
            method: 'POST',
            body: urldata,
            mode: 'no-cors',
            credentials: 'same-origin', 
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                "Content-Type": "application/json;charset=utf-8",
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Headers": "Origin, X-Requested-With, Content-Type, Accept",
                "Access-Control-Allow-Credentials": true
            }
        });

        let data = await response.json();
    
        return data;


    }catch(error){

        let errorMessage = new Error(error);

        console.log(errorMessage);

    }


} */