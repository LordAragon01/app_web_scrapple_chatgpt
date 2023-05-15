(function(){

    "use strict";

    //Get Current Path
    let href = window.location.href;

    //Base Url from Project
    let base_url = document.getElementById('baseurl').getAttribute('data-url');

    //Url To Serach data 
    let url_generatenumber = base_url + '/api/generatenumber';

    //Url to get total of count
    let url_totalcustomer = base_url + '/api/totalcustomer';

    //Get Element Tag for generate Number
    let nextnumberel = document.querySelector('.nextnumber');

    //Current Number from B2C 
    let currentNumberBc = 0;

    //Generate Number
    async function generateNumber(url, sendnumber, method = 'POST'){

        const response = await fetch(url, {
            method: method,
            headers:{
                'Content-Type': "application/json",
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify(sendnumber),
        });

        const result = await response.json();

        if(response.status !== 200){

            throw response.error || new Error(`Request failed with status ${response.status}`);

        }

        return result;


    }

    //Get Total of Customer
    async function getTotalOfCustomer(url){

        const response  = await fetch(url, {
            headers:{
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if(response.status !== 200){

            throw response.error || new Error(`Request failed with status ${response.status}`);

        }

        return data;

    }

    //Dynamic Generate Number when the page is loaded
    document.addEventListener('DOMContentLoaded', function(e){

        //Check correct route
        if(href.includes('penguinb2c')){

            //Counter init
            currentNumberBc ++;

           let generatenumberdata = {
                sendnumber: parseInt(currentNumberBc)
           };
           
           //Send Data from DB
           generateNumber(url_generatenumber, generatenumberdata);

           //transform promisse and get data
           console.log(getTotalOfCustomer(url_totalcustomer));

        }
        
        return;

    });

    //Call Number after Click
    if(nextnumberel !== null){

        let nextnumbercount = document.querySelector('.nextnumber').textContent;

        let totalnumberel = document.querySelector('.totalnumber');
    
        let currentnumber;    

        document.getElementById('callnumber').addEventListener('click', function(e){

            if(nextnumbercount !== null){

                //Counter for nextNumber
                nextnumbercount++;

                currentnumber = nextnumbercount;

                //Check if the nextnumberelemnt is the same of nextnumber value
                if(document.querySelector('.nextnumber').textContent !== 0){

                    nextnumberel.textContent = currentnumber;

                } 

                console.log('Current Number = ', currentnumber);
            

                return currentnumber;

            }

            return;

        });

    }

    return;

})();