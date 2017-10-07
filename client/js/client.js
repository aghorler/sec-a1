const publicKey = "-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----";

function hashAndEncryptPassword(){
	/* Get entered username, and password. */
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;

	/* Check that username and/or password is/are not (an) empty string(s). */
	if(username !== "" && password !== ""){
		/* Check that password exceeds minimum character limit. */
		if(password.length > 5){
			/* Check that username is alphanumeric. */
			if(username.match(/^[a-z0-9]+$/i)){
				/* SHA3 hash password. */
				var hash = CryptoJS.SHA3(password);

				/* Get current time. */
				var currentTime = Math.floor(new Date().getTime() / 1000);

				/* Encrypt with RSA public key. */
				var rsa = new JSEncrypt();
				rsa.setPublicKey(publicKey);

				var encrypted = rsa.encrypt(hash + "&" + currentTime);

				/* Write encrypted password, and username, to hidden form. */
				document.getElementById('formUsername').value = username;
				document.getElementById('formPassword').value = encrypted;

				/* Submit hidden form. */
				document.getElementById("formLogin").submit();
			}
			else{
				alert("Username must be alphanumeric.")
			}
		}
		else{
			alert("Password must exceed 5 characters.")
		}
	}
	else{
		alert("Username and/or password cannot be blank.")
	}
}

function encryptAES(){
	var key = document.getElementById('key').value;
	var keyRepeat = document.getElementById('keyRepeat').value;
	var password = document.getElementById('password').value;

	/* Check that key is equal to repeated key. */
	if(key === keyRepeat){
		/* Check that key is not an empty string. */
		if(key !== "" && password !== ""){
			/* Check that key and password exceed minimum character limit. */
			if(key.length > 5 && password.length > 5){
				/* SHA3 hash password. */
				var hash = CryptoJS.SHA3(password);

				/* Get current time. */
				var currentTime = Math.floor(new Date().getTime() / 1000);

				/* Encrypt with RSA public key. */
				var rsa = new JSEncrypt();
				rsa.setPublicKey(publicKey);

				var encrypted = rsa.encrypt(key + "&" + hash + "&" + currentTime);

				/* Write encrypted key to hidden form. */
				document.getElementById('formKey').value = encrypted;

				/* Submit hidden form. */
				document.getElementById("formGenerate").submit();
			}
			else{
				alert("Key and/or password cannot be less than 6 characters.")
			}
		}
		else{
			alert("Key and/or password cannot be blank.")
		}
	}
	else{
		alert("Keys are not equal.")
	}
}

function getTotal(){
	var aSubTotal = document.getElementById("aQuantity").value * 10;
	var bSubTotal = document.getElementById("bQuantity").value * 15;
	var cSubTotal = document.getElementById("cQuantity").value * 20;

	/* https://stackoverflow.com/a/2901298 */
	document.getElementById("aSubtotal").innerHTML = "&#36;" + aSubTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");;
	document.getElementById("bSubtotal").innerHTML = "&#36;" + bSubTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");;
	document.getElementById("cSubtotal").innerHTML = "&#36;" + cSubTotal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");;
	document.getElementById("quantity").innerHTML = +document.getElementById("aQuantity").value + +document.getElementById("bQuantity").value + +document.getElementById("cQuantity").value;
	document.getElementById("total").innerHTML = "&#36;" + (+aSubTotal + +bSubTotal + +cSubTotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");;
}

function encryptCart(){
	var aQuantity = document.getElementById('aQuantity').value;
	var bQuantity = document.getElementById('bQuantity').value;
	var cQuantity = document.getElementById('cQuantity').value;
	var card = document.getElementById('card').value;
	var key = document.getElementById('key').value;

	if(aQuantity !== "" && bQuantity !== "" && cQuantity !== "" && card  !== "" && key !== ""){
		if(key.length > 5){
			if(card >= 1000000000000000 && card <= 9999999999999999){
				if(aQuantity >= 0 && aQuantity <= 50 && bQuantity >= 0 && bQuantity <= 50 && cQuantity >= 0 && cQuantity <= 50){
					var aesAQuantity = CryptoJS.AES.encrypt(aQuantity, key);
					var aesBQuantity = CryptoJS.AES.encrypt(bQuantity, key);
					var aesCQuantity = CryptoJS.AES.encrypt(cQuantity, key);
					var aesCard = CryptoJS.AES.encrypt(card, key);

					/* Get current time. */
					var currentTime = Math.floor(new Date().getTime() / 1000);

					/* Encrypt data with RSA public key. */
					var rsa = new JSEncrypt();
					rsa.setPublicKey(publicKey);

					var rsaAQuantity = rsa.encrypt(aesAQuantity.toString() + "&" + currentTime);
					var rsaBQuantity = rsa.encrypt(aesBQuantity.toString() + "&" + currentTime);
					var rsaCQuantity = rsa.encrypt(aesCQuantity.toString() + "&" + currentTime);
					var rsaCard = rsa.encrypt(aesCard.toString() + "&" + currentTime);

					/* Write RSA encrypted data to hidden form. */
					document.getElementById('formAQuantity').value = rsaAQuantity;
					document.getElementById('formBQuantity').value = rsaBQuantity;
					document.getElementById('formCQuantity').value = rsaCQuantity;
					document.getElementById('formCard').value = rsaCard;

					/* Submit hidden form. */
					document.getElementById("formCart").submit();
				}
				else{
					alert("The quantity of any item cannot be less than 0, or greater than 50.")
				}
			}
			else{
				alert("Invalid credit card number.")
			}
		}
		else{
			alert("AES key cannot be less than 6 characters.")
		}
	}
	else{
		alert("No field can be blank.")
	}
}

if(document.getElementById("login") !== null){
	document.getElementById("login").addEventListener("click", hashAndEncryptPassword);
}

if(document.getElementById("generate") !== null){
	document.getElementById("generate").addEventListener("click", encryptAES);
}

if(document.getElementById("aQuantity") !== null){
	document.getElementById("aQuantity").addEventListener("change", getTotal);
	document.getElementById("bQuantity").addEventListener("change", getTotal);
	document.getElementById("cQuantity").addEventListener("change", getTotal);
	document.getElementById("process").addEventListener("click", encryptCart);
}
