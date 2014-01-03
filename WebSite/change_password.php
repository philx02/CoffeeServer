<?php
include("header.php");
$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);

function change_password($dbHandle, $memberId, $oldPassword, $newPassword)
{
  $stmt = "SELECT password FROM members WHERE id = ".$memberId;
  if (sha1($oldPassword) != $dbHandle->querySingle($stmt))
  {
    echo "<p>Old password does not match.</p>";
    return;
  }
  $stmt = "UPDATE members SET password = '".sha1($newPassword)."' WHERE id = ".$memberId;
  if ($dbHandle->exec($stmt))
  {
    echo "<p>Password change completed successfully.</p>";
  }
}

if (array_key_exists("ac", $_POST) && $_POST["ac"] == "change_password")
{
  change_password($dbHandle, $_SESSION["id_logged"], $_POST["old_password"], $_POST["new_password"]);
}
?>

<p>Change password</p>

<form action="change_password.php" method="post">
<input type="hidden" name="ac" value="change_password">
<p>Old password<br/><input type="text" size="50" name="old_password" /></p>
<p>New password<br/><input type="password" size="50" name="new_password" /></p>
<p><input type="submit" name="Submit" /></p>
</form>
