<?php
function renderForm($firstname, $lastname, $email, $password, $c_password, $error)
{	
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup | Team Mavin</title>
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
                <label for="firstname">First Name</label>
                <input type="text" name="firstname" pattern="[A-Za-z].{3,}" title="First name should contain Three (3) or more letters!" id="" value="<?php echo $firstname;?>">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" pattern="[A-Za-z].{3,}" title="Last name should contain Three (3) or more letters!" id="" value="<?php echo $lastname;?>">
                 <label for="email">Email</label>
                <input type="email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" id="" value="<?php echo $email;?>">
                <label for="password">Password</label>
                <input type="password" name="password" pattern=".{8,}" title="Password should be Eight (8) or more characters" id="" value="<?php echo $password;?>">
               <label for="password">Confirm Password</label>
                <input type="password" name="c_password" pattern=".{8,}" title="Password should be Eight (8) or more characters" id="" value="<?php echo $c_password;?>">
                <input type="submit" name="submit" value="Sign Up">       
		    <p>  <a href="index.php"> Sign in instead </a></p>


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
	//$fullname = mysql_real_escape_string(htmlspecialchars($_POST['fullname']));
	$firstname = mysqli_real_escape_string($connection, htmlspecialchars($_POST['firstname']));
	$lastname = mysqli_real_escape_string($connection, htmlspecialchars($_POST['lastname']));
	$email = mysqli_real_escape_string($connection, htmlspecialchars($_POST['email']));
	$password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['password']));
	$c_password = mysqli_real_escape_string($connection, htmlspecialchars($_POST['c_password']));
	
	//check to make sure all fields are entered
	if ($firstname =='' || $lastname =='' || $email =='' || $password =='' || $c_password =='')
	{
		//generate error message
		$error = 'ERROR: Fill in all data.';
		
		//if either field is blank, display the form again
		renderForm($firstname, $lastname, $email, $password, $c_password, $error);
		exit;
	}
	
	//check if password and confirm password does not match
	if ($password != $c_password)
	{
		//generate error message
		$error = 'ERROR: Password mismatch. Pls try again.';
		
		//if either field is blank, display the form again
		renderForm($firstname, $lastname, $email, '', '', $error);
		exit;
	}

	//first check if email already exists
	$check_email = mysqli_query($connection, "SELECT email FROM $table_name WHERE email='$email'") or die (mysqli_error($connection));
	//mysql_num_row is counting table row
	$count_check = mysqli_num_rows ($check_email);
	if ($count_check >= 1){
		//generate error message
		$error = 'ERROR: email already exist. Please try another.';
		//if either field is blank, display the form again
		renderForm($firstname, $lastname, '', $password, $c_password, $error);
		exit;
	}
	
	//insert data to the database
	mysqli_query ($connection, "INSERT $table_name SET firstname = '$firstname', lastname= '$lastname', email = '$email', password = '$password'") or die ("Unable to update database.");
	
	//generate successful message
	$error = 'SIGNUP SUCCESSFUL';
	//if either field is blank, display the form again
	renderForm('', '', '', '', '', $error);
	exit;
		
	//once updated, redirect back to the view page
	//header("Location:index.php");		

}
else //if the form hasn't been submitted, get the data from the db and display the form
{
	//renderForm($username, $password_old, $password, $retype, $old_pass, '');
	renderForm('', '', '', '', '', '');	
}
mysqli_close($connection);

?>
