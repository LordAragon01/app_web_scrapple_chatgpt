(function(){

    "use strict";

    //Get Current Path
    let href = window.location.href;

    //Base Url from Project
    let base_url = document.getElementById('baseurl').getAttribute('data-url');

    //Url To Serach data 
    let url_generatenumber = base_url + '/api/generatenumber';

    //Url to get total of count
    let url_customerdata = base_url + '/api/customerdata';

    //Get Element Tag for generate Number
    var nextnumberel = document.querySelector('.nextnumber');

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
    async function getAllCustomerData(url){

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

    
    //Check correct route
    if(href.includes('penguinb2c')){

        //Get LocalStorage Data
        let getLocalStorageData = localStorage.getItem('currentCustomer');

        //Clean data from LocalStorage
        let cleanDataFromLocalSotarge = getLocalStorageData ? JSON.parse(getLocalStorageData) : null;

        //Dynamic Generate Number when the page is loaded
        document.addEventListener('DOMContentLoaded', function(e){

            let prevnumber = document.querySelector('.generatenumber').getAttribute('data-prevnumber');
            let currentip = document.querySelector('.generatenumber').getAttribute('data-ipcurrent');

            //Current Number from B2C 
            let currentNumberBc = parseInt(prevnumber) + 1;

            //console.log(currentNumberBc);
            
            let generatenumberdata = {
                sendnumber: parseInt(currentNumberBc)
            };
            
            //Send Data from DB and json Response
            if(cleanDataFromLocalSotarge === null){

                generateNumber(url_generatenumber, generatenumberdata); 

            }
            
            //Get Html Element for show call number
            let callCurrentNumber = document.querySelector('.callcurrentnumber');

            //Add Dynamic Number for Call Current Customer
            callCurrentNumber.textContent = 19;

            //Generate Number in the front
            //document.querySelector('.generatenumber').textContent = currentNumberBc;

            //Get data from API
            //getAllCustomerData(url_customerdata);

            //Verify Data is the same from DB
            getAllCustomerData(url_customerdata).then((data) => {

                //console.log(data.lastId);

                //Set data in the localStorage and Verify if is null
                if(data.lastId === 0 && cleanDataFromLocalSotarge === null){

                    console.log('Aqui LastId 0 e localStorage null');

                    //Current Data from Customer when access Page
                    let currentCustomer = {
                        lastId: data.lastId,
                        ip: data.ip,
                        call_number: data.call_number,
                        created_at: data.created_at
                    };

                    //Create localstorage
                    localStorage.setItem('currentCustomer', JSON.stringify(currentCustomer));
                    
                    //Atualize Front
                    if(data.lastId == prevnumber){

                        document.querySelector('.generatenumber').textContent = currentNumberBc;

                    }

                }

                //When the value exist in DB and localStorage is null
                if(data.lastId > 0 && cleanDataFromLocalSotarge === null){

                    console.log('Aqui LastId > 0 e localStorage null');

                    //Atualize Front
                    if(data.lastId == prevnumber){

                        //Alterar LocalStorage when the value is bigger then 0
                        let currentCustomer = {
                            lastId: currentNumberBc,
                            ip: data.ip,
                            call_number: data.call_number,
                            created_at: data.created_at
                        };

                        //Create localstorage
                        localStorage.setItem('currentCustomer', JSON.stringify(currentCustomer));

                        document.querySelector('.generatenumber').textContent = currentNumberBc;

                    }
                    
                }

                //Check if localStorage exist
                if(data.lastId > 0 && cleanDataFromLocalSotarge !== null){

                    console.log('Aqui LastId > 0 e localStorage is not null');

                    //After set Data verify if the Id
                    if(cleanDataFromLocalSotarge.lastId === 0){
   
                        //Alterar LocalStorage when the value is 0
                        let currentCustomer = {
                            lastId: data.lastId,
                            ip: data.ip,
                            call_number: data.call_number,
                            created_at: data.created_at
                        };

                        //Create localstorage
                        localStorage.setItem('currentCustomer', JSON.stringify(currentCustomer));

                        //Atualize Front
                        if(data.lastId == prevnumber){

                            document.querySelector('.generatenumber').textContent = data.lastId;

                        }

                    }

                    //Verify if the localStorage is not null and id is not 0
                    if(cleanDataFromLocalSotarge.lastId !== 0){

                        //Verify if is the same IP in LocalStorage
                        if(data.ip == cleanDataFromLocalSotarge.ip){

                            //Atualize Front
                            document.querySelector('.generatenumber').textContent = cleanDataFromLocalSotarge.lastId;

                        }else{

                            console.log('Not the same IP', data.lastId);
                            console.log(cleanDataFromLocalSotarge.lastId);
                            console.log(data.allips.includes(currentip));

                            //Atualize Front
                            if(data.ip !== currentip && !data.allips.includes(currentip)){

                                console.log('Not the same IP - scopo', data.lastId);

                                //Send Data from DB and json Response
                                generateNumber(url_generatenumber, generatenumberdata); 

                                //Alterar LocalStorage when the value is bigger then 0
                                let currentCustomer = {
                                    lastId: currentNumberBc,
                                    ip: currentip,
                                    call_number: data.call_number,
                                    created_at: data.created_at
                                };

                                //Create localstorage
                                localStorage.setItem('currentCustomer', JSON.stringify(currentCustomer));

                                console.log(cleanDataFromLocalSotarge.lastId);

                                document.querySelector('.generatenumber').textContent = currentNumberBc;

                            }else{

                                //Atualize Front
                                document.querySelector('.generatenumber').textContent = cleanDataFromLocalSotarge.lastId;

                            }

                        }

                        //Verify if the current number is call
                        if(callCurrentNumber.textContent == cleanDataFromLocalSotarge.lastId){

                            //console.log(callCurrentNumber.textContent);
                            //Remove data from LocalStorage
                            localStorage.removeItem('currentCustomer');

                        }

                        console.log('Fim do Script');

                    }

                    //console.log("Zona Neutra", cleanDataFromLocalSotarge);

                }

                //console.log(data.lastId);
                //console.log(cleanDataFromLocalSotarge);

                return;

            }).catch((error) => {

                throw new Error(error);

            });

            return;

        });

    }
        
    

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