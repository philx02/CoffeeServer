<?php
session_start(); 
include("passwords.php"); 

if (array_key_exists($_SESSION["logged"],$USERS))
{
  //// check if user is logged or not  
  echo "You are logged in."; //// if user is logged show a message  
}
else
{
  //// if not logged show login form 
  echo '<form action="main.php" method="post"><input type="hidden" name="ac" value="log">'; 
  echo 'Username: <input type="text" name="username" />'; 
  echo 'Password: <input type="password" name="password" />'; 
  echo '<input type="submit" value="Login" />'; 
  echo '</form>'; 
}
?>
