let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;
//let url_local = protocol + '//' + host + '/api/openapiconchat';
let url_local = protocol + '//' + host + ':8080/api/openapiconchat';
let url_stage = "http://192.168.20.112/projects_mvp/public/api/openapiconchat";
let default_url;

//Get Base Search Url
window.addEventListener('load', function(){

    return default_url = host.includes('projects_mvp.test') ? url_local : url_stage;

});

//Get Data From Open Api with a Promise
async function getDataOpenApi(prompt) {

    "use strict";

    let url = default_url !== undefined ? default_url : '';
    
    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          'Content-Type': "application/json",
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({chatindicateprompt: prompt}),
      });

      const data = await response.json();
      if (response.status !== 200) {
        throw data.error || new Error(`Request failed with status ${response.status}`);
      }

      return data;

    } catch(error) {
     
      alert(error.message);

    }
}


//Modify Behavior of Loading Element
/* window.addEventListener('scroll', function(e){

  let loadingEl = document.getElementById('chatgptform');

  e.target = loadingEl;

  let getElDistanceFromTop = loadingEl.getBoundingClientRect();

  //Add dynamic top for loading element
  //let addDistanceFromTop = getElDistanceFromTop.top / 2;

  //Real Distance
  let addDistanceFromTop = getElDistanceFromTop.top > 850 ? Math.ceil(getElDistanceFromTop.top / 1.5) : Math.ceil(getElDistanceFromTop.top * 1.2);
  
  console.log(addDistanceFromTop);

  //document.getElementById('loading').style.top= 'calc('+ getElDistanceFromTop.top +' / 2)';
  $('#loading').css({
    'top': addDistanceFromTop + 'px'
  });

  console.log(getElDistanceFromTop);

}); */

//Get Data and send Result for Front
document.querySelector('.chatgptform').addEventListener('submit', function(e){

    e.preventDefault();

    "use strict";

    //Disabled Button
    //let btnchatgpt = document.getElementById('chatgptbtnconv');
    $('#chatgptbtnconv').prop('disabled', true);
    //btnchatgpt.setAttribute('disabled', true);

    //Add Loader
    if(!document.querySelector('.loadingform').classList.contains('activedload')){

        document.querySelector('.loadingform').classList.add('activedload');

    }

    //Remove old searchs structures
    //let resultList = [...document.getElementById('resultgptchat').children];

    /* if(resultList.length > 0){

        //Remove old search
        resultList[0].remove();

    } */

    //Get Value from input
    let prompt = document.getElementById('chatpromptsearch').value;

    //Verify prompt Value
    if(typeof prompt === 'string' && !containsOnlyNumbers(prompt)){

        //Send value to search in the Api
        let contentResponse = getDataOpenApi(prompt.trim());

        console.log(contentResponse);

        //Structure a Promise and get Object Data
        contentResponse.then((data) => {

            //Create List of Responses
            if(Array.isArray(data)){

                data.forEach(value => {

                  console.log(value);

                  let role = value.role;
                  let content = value.content;
      
                  if(role == 'user'){

                      let text = '<p><strong>'+ role.trim().toUpperCase() +'</strong></p>';
                      text += '<p>' + content.trim() + '</p>';
          
                      //Add search in the Front
                      $(text).appendTo($('#resultgptchat'));

                  }else{

                      let text = '<p><strong>'+ role.trim().toUpperCase() +'</strong></p>';
                      text += '<p>' + content.trim() + '</p>';
          
                      //Add search in the Front
                      $(text).appendTo($('#resultgptchat'));

                  }

                  
              });

            }else{

                let text = '<p><strong>'+ data.content.trim() +'</strong></p>';
    
                //Add search in the Front
                $(text).appendTo($('#resultgptchat'));

            }
         


            //Remove Loader
            if(document.querySelector('.loadingform').classList.contains('activedload')){

                document.querySelector('.loadingform').classList.remove('activedload')

            }

            //Enabled Button
            $('#chatgptbtnconv').prop('disabled', false);

            //Clean Input
            document.getElementById('chatpromptsearch').value = '';
        

        }).catch((error) => {

            console.error(error.message);

        });

    }else{

        alert('Por favor só informar prompts válidos');

        //Enabled Button
        $('#chatgptbtnconv').prop('disabled', false);

    }

});

//Check if the Value from Input contain only number
function containsOnlyNumbers(str) {
    return /^[0-9]+$/.test(str);
}

/*===Typewrite Effect==*/
/* const typedText = $('.result').find('p');
const cursor = document.querySelector(".cursor");

const textArray = ["Web Developer", "Web Designer", "Tutor", "Learner..."];

let textArrayIndex = 0;
let charIndex = 0;
 */
/* const erase = () => {
  if (charIndex > 0) {
    cursor.classList.remove('blink');
    typedText.textContent = textArray[textArrayIndex].slice(0, charIndex - 1);
    charIndex--;
    setTimeout(erase, 80);
  } else {
    cursor.classList.add('blink');
    textArrayIndex++;
    if (textArrayIndex > textArray.length - 1) {
      textArrayIndex = 0;
    }
    setTimeout(type, 1000);
  }
} */

/* const type = () => {
  if (charIndex <= textArray[textArrayIndex].length - 1) {
    cursor.classList.remove('blink');
    typedText.textContent += textArray[textArrayIndex].charAt(charIndex);
    charIndex++;
    setTimeout(type, 120);
  } else {
    cursor.classList.add('blink');
    //setTimeout(erase, 1000);
  }
} */

/* document.addEventListener("DOMContentLoaded", () => {
    type();
}) */