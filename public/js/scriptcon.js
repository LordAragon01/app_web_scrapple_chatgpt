let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;
//let url_local = protocol + '//' + host + '/api/openapicon';
let url_local = protocol + '//' + host + ':8080/api/openapicon';
let url_stage = "http://192.168.20.112/projects_mvp/public/api/openapicon";
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
        body: JSON.stringify({indicateprompt: prompt}),
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

//Get Data and send Result for Front
document.querySelector('.searchchatgpt_form').addEventListener('submit', function(e){

    e.preventDefault();

    "use strict";

    //Disabled Button
    //let btnchatgpt = document.getElementById('chatgptbtn');
    $('#chatgptbtn').prop('disabled', true);
    //btnchatgpt.setAttribute('disabled', true);

    //Add Loader
    if(!document.querySelector('.loading').classList.contains('activedload')){

        document.querySelector('.loading').classList.add('activedload')

    }

    //Remove old searchs structures
    let resultList = [...document.getElementById('resultgpt').children];

    if(resultList.length > 0){

        //Remove old search
        resultList[0].remove();

    }

    //Get Value from input
    let prompt = document.getElementById('promptsearch').value;

    //Verify prompt Value
    if(typeof prompt === 'string' && !containsOnlyNumbers(prompt)){

        //Send value to search in the Api
        let contentResponse = getDataOpenApi(prompt.trim());

        console.log(contentResponse);

        //Structure a Promise and get Object Data
        contentResponse.then((data) => {

            let content = data.content;
            let text = '<p>' + content.trim() + '<span class="cursor blink">&nbsp;</span></p>';
            //let text = content.trim();
            //let textArray = text.split(" ");

            //Typewriter sequence
            /* let typedText = resultList[0];
            let cursor = document.querySelector(".cursor");
            let textArrayIndex = 0;
            let charIndex = 0; */

            //console.log(typedText);

            /* textArray.forEach((value) => {

                console.log(value);
                $(typedText).text(value);
                typedText.textContent += value;
                cursor.classList.remove('blink');

            }); */
     
            //Add search in the Front
            $(text).appendTo($('#resultgpt'));

            //Remove Loader
            if(document.querySelector('.loading').classList.contains('activedload')){

                document.querySelector('.loading').classList.remove('activedload')

            }

            //Enabled Button
            $('#chatgptbtn').prop('disabled', false);
        

        }).catch((error) => {

            console.error(error.message);

        });

    }else{

        alert('Por favor só informar prompts válidos');

        //Enabled Button
        $('#chatgptbtn').prop('disabled', false);

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