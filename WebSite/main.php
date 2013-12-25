<?php
if ($_POST["ac"]=="log")
{
  /// do after login form is submitted
  $dbHandle = new SQLite3('test.db', SQLITE3_OPEN_READWRITE);
  $stmt = "SELECT password FROM members WHERE username = '".$_POST["username"]."'";
  $result = $dbHandle->query($stmt);
  $shaPasswordFromDb = $result->fetchArray()["password"];
  if ($shaPasswordFromDb == sha1($_POST["password"]))
  {
    $_SESSION["logged"]=$_POST["username"];
  }    
  else
  { 
    echo 'Incorrect username/password. Please, try again.'; 
  }
}
?>

<a href="logout.php">logout</a>
