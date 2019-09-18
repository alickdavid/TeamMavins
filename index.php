<?php
function renderForm($email, $password, $error)
{	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to Team Mavin | Signin</title>
    <link rel="stylesheet" href="css/style.css">
    
</head>
<body>
    <div class="container">
        <div class="intro">
            <h1> Join the <span>fun</span></h1>
        </div>
        <div class="inputs">
            <form action="" method="post" role="form">
                <?php
					//if there is any error, displays them
					if ($error !='')
					{
					echo '<div align="center" style="color: yellow;">'.$error.'</div>';
					}
				?>          
            <label for="email">Email</label>
                <input type="email" name="email" id="">
                <label for="email">Password</label>
                <input type="password" name="password" id="">
                <input type="submit" name="submit" value="Join Now">
                <p>  <a href="signup.php"> Click here to create account </a></p>

            </form>
        </div>
    </div>
</body>
</html>

<?php
}
$table_name = "users";
//code to connect to database
include ("config/db_config.php");

$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE) or die (mysqli_error());
mysqli_select_db ($connection, DB_DATABASE) or die (mysqli_error());

//check if the form has been submitted. If it has, start to process the form and save it to the database
if (isset($_POST['submit']))
{
	//get form data, making sure it is valid
	$email = mysqli_real_escape_string($connection, htmlspecialchars($_POST['email']));
	$password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
		
	//check to make sure all fields are entered
	if ($email =='' || $password =='')
	{
		//generate error message
		$error = 'ERROR: Please enter all credentials.';
		
		//if either field is blank, display the form again
		renderForm($email, $password, $error);
		exit;
	}
	
	//first check if email already exists
	$check_pass = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email' and password = '$password'") or die (mysqli_error($connection));
	//mysql_num_row is counting table row
	$count_check = mysqli_num_rows ($check_pass);
	$row = mysqli_fetch_array($check_pass);
	
	if ($count_check == 1){
		//register $firstname, $lastname, $email and redirect to file dashboard page"
		session_start();
		$_SESSION['user_id'] = $row["id"];

		
		$_SESSION['firstname'] = $row['firstname'];
		$_SESSION['lastname'] = $row["lastname"];
		$_SESSION['email'] = $row["email"];

		//once updated, redirect back to the view page
		header("Location:dashboard.php");		
		exit;
	}
	else
	{
		//generate error message
		$error = 'ERROR: Authentication failed, pls try again.';
		//if either field is blank, display the form again
		renderForm($email, $password, $error);
		exit;
	}

}
else //if the form hasn't been submitted, get the data from the db and display the form
{
	//renderForm($username, $password_old, $password, $retype, $old_pass, '');
	renderForm('', '', '');	
}
mysqli_close($connection);

?>