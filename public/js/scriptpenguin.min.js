(function(){

    "use strict";

    let nextnumberel = document.querySelector('.nextnumber');

    let nextnumbercount = document.querySelector('.nextnumber').textContent;

    let totalnumberel = document.querySelector('.totalnumber');

    let currentnumber;

    //Call Number after Click
    document.getElementById('callnumber').addEventListener('click', function(e){

        //Counter for nextNumber
        nextnumbercount++;

        currentnumber = nextnumbercount;

        //Check if the nextnumberelemnt is the same of nextnumber value
        if(document.querySelector('.nextnumber').textContent !== 0){

            nextnumberel.textContent = currentnumber;

        } 

        console.log('Current Number = ', currentnumber);
        

        return currentnumber;

    });



})();