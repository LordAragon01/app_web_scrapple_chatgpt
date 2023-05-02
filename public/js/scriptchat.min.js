let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;

//Url To Serach data 
//let url_local = protocol + '//' + host + '/api/openapiconchat';
let url_local = protocol + '//' + host + ':8080/api/openapiconchat';
let url_stage = "http://192.168.20.112/projects_mvp/public/api/openapiconchat";
let default_url;

//Url to Remnove data from DB
//let url_local = protocol + '//' + host + '/api/openapiconclearchat';
let url_local_del = protocol + '//' + host + ':8080/api/openapiconclearchat';
let url_stage_del = "http://192.168.20.112/projects_mvp/public/api/openapiconclearchat";
let default_url_del;

//List of Urls
let listDefaultUrl = [];

//Get Base Search Url
window.addEventListener('load', function(){

  //Verify Url
  listDefaultUrl.push(getUrlToApi(default_url, url_local, url_stage));
  listDefaultUrl.push(getUrlToApi(default_url_del, url_local_del, url_stage_del));

  return listDefaultUrl;

});

//Get Data From Open Api with a Promise
async function getDataOpenApi(url, prompt) {

    "use strict";
    
    try {
      const response = await fetch(url, {
        method: "POST",
        headers: {
          'Content-Type': "application/json",
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify(prompt),
      });

      const data = await response.json();
      if (response.status !== 200) {
        throw data.error || new Error(`Request failed with status ${response.status}`);
      }

      return data;

    } catch(error) {

      console.log(error.message);
     
      alert("Ocorreu um Erro, Favor contatar a equipa do Software & Desenvolvimento da BiBright");

    }
}


//Modify Behavior of Loading Element
/*window.addEventListener('scroll', function(e){

  let loadingEl = document.getElementById('chatgptform');

  //e.target = loadingEl;

  let getElDistanceFromTop = loadingEl.getBoundingClientRect();

  //Dynamic scroll element for bottom of page
  if(getElDistanceFromTop.top >= 799){

    window.scrollTo(0, document.body.scrollHeight);

    console.log('Aqui');

  }

  //Add dynamic top for loading element
  //let addDistanceFromTop = getElDistanceFromTop.top / 2;

  //Real Distance
  //let addDistanceFromTop = getElDistanceFromTop.top > 850 ? Math.ceil(getElDistanceFromTop.top / 1.5) : Math.ceil(getElDistanceFromTop.top * 1.2);
  
  //console.log(addDistanceFromTop);

  //document.getElementById('loading').style.top= 'calc('+ getElDistanceFromTop.top +' / 2)';
  $('#loading').css({
    'top': addDistanceFromTop + 'px'
  });

  //console.log(getElDistanceFromTop);

});*/

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

    //Get Value from input
    let prompt = document.getElementById('chatpromptsearch').value;

    //console.log(prompt);

    //Verify prompt Value
    if(typeof prompt === 'string' && !containsOnlyNumbers(prompt) && prompt !== ''){

        //Verify url to send Data from post
        let url = listDefaultUrl[0] !== undefined ? listDefaultUrl[0] : '';

        //Send value to search in the Api
        let contentResponse = getDataOpenApi(url, {chatindicateprompt: prompt.trim()});

        console.log(contentResponse);

        //Structure a Promise and get Object Data
        contentResponse.then((data) => {

            //Create List of Responses
            if(Array.isArray(data)){

                data.forEach(value => {

                  console.log(value);

                  let role = value.role;
                  let content = value.content;
      
                  if(role == 'system'){

                      let text = '<p><strong>'+ role.trim().toUpperCase() +'</strong></p>';
                      text += '<p>' + content.trim() + '</p>';
          
                      //Add search in the Front
                      $(text).appendTo($('#resultgptchat'));

                  }else if(role == 'user'){

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


            //Dynamic scroll element for bottom of page
            let getElDistanceFromTop = this.getBoundingClientRect();

            if(getElDistanceFromTop.top >= 799){
          
              window.scrollTo(0, document.body.scrollHeight);
          
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

        //Remove Loader
        if(document.querySelector('.loadingform').classList.contains('activedload')){

          document.querySelector('.loadingform').classList.remove('activedload')

        }

    }

});

//Clear Chat and Data Base
$('#chatgptbtnconvdel').on('click', function(){

    "use strict";

    //Disabled Button
    $('#chatgptbtnconvdel').prop('disabled', true);

    //Add Loader
    if(!document.querySelector('.loadingform').classList.contains('activedload')){

      document.querySelector('.loadingform').classList.add('activedload');

    }

    //Confirm action before erase DB
    if(confirm('Ao Limpar o Chat também será limpo a relação de mensagens') === true){

      //Get Children chat elements
      let resultList = [...$('#resultgptchat').children()];

      //Validate children existent 
      if(resultList !== undefined){

        if(resultList.length > 0){

          //Verify url to send Data from post
          let url = listDefaultUrl[1] !== undefined ? listDefaultUrl[1] : '';

          //Send value to search in the Api
          let response = getDataOpenApi(url, {removechatdata: true});

          //Get object confirm
          response.then((data) => {

            if(data.confirm === true){

              //Remove old chat elements
              removeElements(resultList);

            }else{

              alert("Ocorreu um Erro, Favor contatar a equipa do Software & Desenvolvimento da BiBright");

            }

          }).catch((error) => {

            console.error(error.message);

          });

        
          //Remove Loader
          if(document.querySelector('.loadingform').classList.contains('activedload')){

            document.querySelector('.loadingform').classList.remove('activedload')

          }
          
          //Enabled Button
          $('#chatgptbtnconvdel').prop('disabled', false);

        }else{

          alert("Chat ainda não foi iniciado");

          //Remove Loader
          if(document.querySelector('.loadingform').classList.contains('activedload')){

            document.querySelector('.loadingform').classList.remove('activedload')

          }
          
          //Enabled Button
          $('#chatgptbtnconvdel').prop('disabled', false);
    
        }
        
      }


    }

    //Remove Loader
    if(document.querySelector('.loadingform').classList.contains('activedload')){

      document.querySelector('.loadingform').classList.remove('activedload')

    }
    
    //Enabled Button
    $('#chatgptbtnconvdel').prop('disabled', false);
 
    return;

});

//Check if the Value from Input contain only number
function containsOnlyNumbers(str) {
    return /^[0-9]+$/.test(str);
}

//Remove Elements from Chat
function removeElements(list){

  list.forEach((value) => {

    value.remove();

  });

  return list = [];

}

//Get Url to API
function getUrlToApi(defaulturl, urlocal, urlstage){

  return defaulturl = host.includes('projects_mvp.test') ? urlocal : urlstage;

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