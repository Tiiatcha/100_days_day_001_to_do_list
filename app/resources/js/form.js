// import token from './modules/token.js';
// import validate from './modules/form__validate.js';
const getNewToken = () => {
	// I chose to not use jQuery Ajax function but instead a vanilla http/ajax request
	// instantiate a new http object
	return new Promise((resolve, reject) => 
	{
		let xhttp = new XMLHttpRequest()
		// watch for changes to http status
		xhttp.onreadystatechange = function(){
			// if all goes well then
			if(this.readyState == 4 && this.status == 200){
				// return the server response. In this case it will be the output of token.php
				// I have set token to be global variable
				token = this.responseText;
				resolve(localStorage.setItem('appToken',token));
			}
		};
		// open the http request
		xhttp.open('POST','app/handlers/token.php',true);
		// send the http request which will be picked up by the previous xhttp.onreadystatechange
		xhttp.send();
		//console.log(`token: ${token}`)
	})
}
const form_submit = (e, mp = {}, mo = {}) => 
{
    //option = $.parseJSON(option);
        // o = options passed as an object eg: {option1:'value1',option2:'value2'}
        // options expected:
        // Option   |   Value
        // Modal    |   Boolean
        // id_field |   This is the html id of the field to update with a new id (when a new record is created)
        // l_url    |   This is the URL of the content you wish to refresh
        // l_target |   Target id of field to insert refreshed data into.
        // r_id     |   This is the id of the record to refresh
        // params   |   fields and values to pass

    return new Promise((resolved, reject) => 
    {
        let contentType = '';
        getNewToken().then((resolve) =>
        {
            let modal = false;
            let options = JSON.parse(e.dataset.options);
            options = {...options,...mo};
            //if(typeof options.refresh_animation == 'undefined' || options.refresh_animation == true) { document.querySelector(".ng-loading-container").classList.add("ng-loading-container-show") }
            let formData = new FormData(e);
            Object.keys(mp).forEach((key)=>{formData.append(key,mp[key])});
            if(typeof options.dbprops != 'undefined')
            {
                for(const dbprop of Object.keys(options.dbprops))
                {
                    formData.append(dbprop,options.dbprops[dbprop]);
                }
            }
            //formData.append('token',localStorage.getItem('appToken'));
            const url = typeof options.altAction == 'undefined' ? e.action : options.altAction;
            const method = e.method;
            if(typeof options != 'undefined' && options !== false)
            {
                try{options=JSON.parse(options)}
                catch(err){options=options}
                
            }
            
            for(const item in mp)
            {
                formData.append(item,mp[item]);
            }
            formData.append('token', localStorage.getItem('appToken'));

            // display loading animation
            if(typeof options.refresh_animation == 'undefined' || options.refresh_animation == true) 
            { 
                //document.querySelector(".ng-loading-container").classList.add("ng-loading-container-show") 
            }
            
            fetch(url,{
                method:'post',
                //headers: {'Content-Type': 'application/json'},
                body:formData
            })
            .then((response)=>
            {
                console.log(Response.statusText);
                const contentType = response.headers.get('content-type');
                if (contentType.includes('application/json')) 
                {
                    return response.json();
                }
                else
                {
                    return response.text();
                }
            }).then((response)=>{
                console.log(response);
                //if(options.response == 'html' && typeof options.r_target){document.querySelector(options.r_target).innerHTML = response}
                if(typeof options.return_result !== 'undefined' && options.return_result === true)
                {
                    resolved(response);
                }
                if (contentType == 'json') 
                {
                    // build and display alert
                }
                else
                {
                   if(typeof options.r_target != 'undefined'){document.querySelector(options.r_target).innerHTML = response}
                }
                if (typeof options.refreshContentArr != 'undefined') { refreshContents(options.refreshContentArr); }
                if (typeof options.amendContentArr != 'undefined') { amendContents(result.data, options.amendContentArr); }
                if (typeof options.message != 'undefined') { build_alert(response, options); }
            }).catch((err)=>{
                console.log(err);
            }).finally(()=>{
                //document.querySelector(".ng-loading-container").classList.remove("ng-loading-container-show");
                if(typeof options.modal != 'undefined'){modal = options.modal};
            });
        });
    });

}

const form_submit_without = (url, params, o) => 
{
  
    return new Promise((resolved, reject) => 
    {
        getNewToken().then((resolve) => 
        {
            var options = o
            let formData = new FormData();
            for(const item in params)
            {
                formData.append(item,params[item]);
            }
            formData.append('token', localStorage.getItem('appToken'));

            // display loading animation
            if(typeof options.refresh_animation == 'undefined' || options.refresh_animation == true) 
            { 
                document.querySelector(".ng-loading-container").classList.add("ng-loading-container-show") 
            }
            fetch(url,{
                method:'post',
                //headers: {'Content-Type': 'application/json'},
                body:formData
            })
            .then((response)=>
            {
                console.log(Response.statusText);
                const contentType = response.headers.get('content-type');
                if (contentType.includes('application/json')) 
                {
                    return response.json();
                }
                else
                {
                    return response.text();
                }
            }).then((response)=>{
                document.querySelector(".ng-loading-container").classList.remove("ng-loading-container-show");
                if(o.return_result !== 'undefined' && o.return_result === true){
                    resolved(response);
                }
                if (contentType == 'json') 
                {
                    // build and display alert
                }
                else
                {
                    // display some content
                }
            }).catch((err)=>{
                document.querySelector(".ng-loading-container").classList.remove("ng-loading-container-show");
                console.log(err);
            });
        });
    });

}