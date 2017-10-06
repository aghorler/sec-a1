function hash(){
	var publicKey = "-----BEGIN PUBLIC KEY-----MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAzdxaei6bt/xIAhYsdFdW62CGTpRX+GXoZkzqvbf5oOxw4wKENjFX7LsqZXxdFfoRxEwH90zZHLHgsNFzXe3JqiRabIDcNZmKS2F0A7+Mwrx6K2fZ5b7E2fSLFbC7FsvL22mN0KNAp35tdADpl4lKqNFuF7NT22ZBp/X3ncod8cDvMb9tl0hiQ1hJv0H8My/31w+F+Cdat/9Ja5d1ztOOYIx1mZ2FD2m2M33/BgGY/BusUKqSk9W91Eh99+tHS5oTvE8CI8g7pvhQteqmVgBbJOa73eQhZfOQJ0aWQ5m2i0NUPcmwvGDzURXTKW+72UKDz671bE7YAch2H+U7UQeawwIDAQAB-----END PUBLIC KEY-----";

	/* Get entered username, and password. */
	var username = document.getElementById('username').value;
	var password = document.getElementById('password').value;

	/* Check that username and/or password is/are not (an) empty string(s). */
	if(username !== "" && password !== ""){
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
		alert("Username and/or password cannot be blank.")
	}
}

document.getElementById("login").addEventListener("click", hash);
