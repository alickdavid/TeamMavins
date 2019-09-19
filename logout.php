<?php

//START A SESSION
   session_start();
   require_once('config/db_config.php');
   //DESTROY THE SESSION
   session_destroy();


//REDIRECT TO LOGIN PAGE AFTER DESTROY
   header("location:index.php");

?>

