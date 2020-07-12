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
export default getNewToken;