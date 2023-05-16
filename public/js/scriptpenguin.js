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

    //Url to get select data from customer
    let url_selectdata = base_url + '/api/currentnumber';

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
            let selecteid = document.querySelector('.generatenumber').getAttribute('data-selectid');
            let totalcustomer = document.querySelector('.missingnumber').getAttribute('data-totalcustomer');

            //Current Number from B2C 
            let currentNumberBc = parseInt(prevnumber) + 1;

            //console.log(currentNumberBc);
            
            let generatenumberdata = {
                sendnumber: parseInt(currentNumberBc)
            };
            
            
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
                let allips = [];

                //Get List of ips
                if(data.allips !== null){

                    data.allips.forEach((value) => {

                        console.log(value.allips);

                        allips.push(value.allips);

                    });

                }

                //Send Data to DB and json Response
                if(cleanDataFromLocalSotarge === null && data.allips === null){

                    console.log('Aqui 1');

                    generateNumber(url_generatenumber, generatenumberdata); 

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

                    return;

                }

                //Verify db and create localStorage
                if(cleanDataFromLocalSotarge === null && allips.includes(currentip)){

                    console.log('Aqui 2');

                    ///generateNumber(url_generatenumber, generatenumberdata); 

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

                        document.querySelector('.generatenumber').textContent = data.lastId;

                    }

                    return;

                }

                //Verify db and create localStorage and save data
                if(cleanDataFromLocalSotarge === null && !allips.includes(currentip)){

                    console.log('Aqui 3');

                    generateNumber(url_generatenumber, generatenumberdata); 

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

                        document.querySelector('.generatenumber').textContent = data.lastId;

                    }

                    return;

                }

                //Verify db and create localStorage and save data
                if(cleanDataFromLocalSotarge !== null && !allips.includes(currentip)){

                    console.log('Aqui 4');

                    console.log(allips);

                    console.log(allips.includes(currentip));

                    generateNumber(url_generatenumber, generatenumberdata); 

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

                        document.querySelector('.generatenumber').textContent = data.lastId;

                    }

                    return;

                }

                //Verify ip insert in db when is true
                if(cleanDataFromLocalSotarge !== null && allips.includes(currentip)){

                    console.log('Aqui 5');

                    //generateNumber(url_generatenumber, generatenumberdata); 

                    console.log(allips);

                    console.log(allips.includes(currentip));

                    //Verify id
                    if(cleanDataFromLocalSotarge.ip === null){

                        //Get current Ip
                        let indexIp = allips.indexOf(currentip);
                        let currentIp = allips[indexIp];

                        //Indicate method for post
                        let indicateIp = {
                            selectip: currentIp
                        };

                        //Get select Data from IP
                        let selectDataFromCustomer = generateNumber(url_selectdata, indicateIp); 

                        console.log(currentIp);
                        console.log(selectDataFromCustomer);

                        selectDataFromCustomer.then((valselect) => {

                            //Current Data from Customer when access Page
                            let currentCustomer = {
                                id: valselect.id,
                                ip: valselect.ip,
                                call_number: valselect.call_number,
                                created_at: valselect.created_at
                            };

                            //Create localstorage
                            localStorage.setItem('currentCustomer', JSON.stringify(currentCustomer));

                            console.log(selecteid);

                            //Att Front
                            document.querySelector('.generatenumber').textContent = selecteid;


                        }).catch((error) => {

                            throw new Error(error);

                        });



                    }else{

                        //Att Front
                        document.querySelector('.generatenumber').textContent = cleanDataFromLocalSotarge.id;

                    }
                    

                    //console.log(totalcustomer);
                    //console.log(data.lastId);

                    //Notification
                    if(parseInt(totalcustomer) !== parseInt(cleanDataFromLocalSotarge.id)){

                        //Estimate counter for menor value
                        let estimateCounter = totalcustomer > 1 ? parseInt(totalcustomer) - parseInt(data.lastId) : data.lastId;

                        //Add Missing Number
                        document.querySelector('.missingnumber').textContent = estimateCounter;

                    }else{

                        //Send Notification
                        document.querySelector('.missingnumber').textContent = "Sua Vez";

                    }


                    return;

                }

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