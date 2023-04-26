let href = window.location.href;
let host = window.location.hostname;
let protocol = window.location.protocol;
let url_local = protocol + '//' + host + ':8080/api/openapicon';
let url_stage = "http://192.168.20.112/projects_mvp/public/api/openapicon";
let default_url;

async function getDataOpenApi(prompt) {
    
    try {
      const response = await fetch(default_url !== undefined ? default_url : '', {
        method: "POST",
        headers: {
          'Content-Type': "application/json",
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: prompt,
      });

      const data = await response.json();
      if (response.status !== 200) {
        throw data.error || new Error(`Request failed with status ${response.status}`);
      }

      return data.result;

    } catch(error) {
     
      alert(error.message);

    }
}

document.querySelector('.searchchatgpt_form').addEventListener('submit', function(e){

    e.preventDefault();

    let prompt = document.getElementById('promptsearch').value;

    let contentResponse = getDataOpenApi(prompt);

    console.log(contentResponse);

});