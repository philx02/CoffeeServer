<?php
include("CoffeeDb.php");

session_start();

if (array_key_exists("ac", $_POST) && $_POST["ac"] == "log")
{
  /// do after login form is submitted
  $dbHandle = new CoffeeDb();
  $stmt = "SELECT id, password, admin FROM members WHERE username = '".$_POST["username"]."'";
  $result = $dbHandle->querySingle($stmt, true);
  $shaPasswordFromDb = $result["password"];
  if ($shaPasswordFromDb == sha1($_POST["password"]))
  {
    $_SESSION["id_logged"] = $result["id"];
    $_SESSION["admin"] = $result["admin"];
    header("Location: main.php");
  }    
  else
  { 
    echo 'Incorrect username/password. Please, <a href=".">try again</a>.'; 
  }
}
else
{
  //// if not logged show login form 
  echo '<form action="index.php" method="post"><input type="hidden" name="ac" value="log">'; 
  echo 'Username: <input type="text" name="username" />'; 
  echo 'Password: <input type="password" name="password" />'; 
  echo '<input type="submit" value="Login" />'; 
  echo '</form>'; 
}
?>
