
<?php

// TO CHECK THE USER THAT IS LOGGED IN
	function userLoggedIn(){
		if(isset($_SESSION['user_id'])){
		  return $_SESSION['user_id']; 
		}else{
			return false;
		}
	}


?>