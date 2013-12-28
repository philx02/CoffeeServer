<?php
function check_logged()
{ 
  global $_SESSION;
  if (!array_key_exists("id_logged", $_SESSION))
  {
    header("Location: index.php");
  }
}
?>
