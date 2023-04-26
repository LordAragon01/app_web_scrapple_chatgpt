let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;
let url_local = protocol + '//' + host + ':8080/api/openapicon';
let url_stage = "http://192.168.20.112/projects_mvp/public/api/openapicon";
let default_url;

//Get Base Search Url
window.addEventListener('load', function(){

    return default_url = host.includes('projeto_fox.test') ? url_local : url_stage;

});

//Get Data From Open Api with a Promise
async function getDataOpenApi(prompt) {

    //let url = default_url !== undefined ? default_url : '';
    
    try {
      const response = await fetch(url_local, {
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

    let resultList = [...document.getElementById('resultgpt').children];

    if(resultList.length > 0){

        //Remove old search
        resultList[0].remove();

    }

    //Get Value from input
    let prompt = document.getElementById('promptsearch').value;

    //Send value to search in the Api
    let contentResponse = getDataOpenApi(prompt.trim());

    console.log(contentResponse);

    //Structure a Promise and get Object Data
    contentResponse.then((data) => {

        let content = data.content;
        let text = '<p>' + content.trim() + '</p>';

        //console.log(text);
        //Add search in the Front
        $(text).appendTo($('#resultgpt'));
    

    }).catch((error) => {

        console.error(error.message);

    });

});