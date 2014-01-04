<?php
include("header.php");
$dbHandle = new CoffeeDb();

function member_addition($dbHandle, $name, $username, $email, $rfid, $initialDeposit)
{
  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
  {
    echo "<p>Invalid value for email address (".$email.").</p>";
    return;
  }
  if (!is_numeric($initialDeposit))
  {
    echo "<p>Invalid value for initial deposit amount (".$initialDeposit.").</p>";
    return;
  }
  $initialDepositCents = floor($initialDeposit * 100);
  $password = substr(str_shuffle(md5(time())), 0, 8);
  $passwordSha1 = sha1($password);
  $stmt = "INSERT INTO members (userid, username, password, name, email, balance_cents, admin) VALUES ('".$rfid."', '".$username."', '".$passwordSha1."', '".$name."', '".$email."', ".$initialDepositCents.", 0)";
  //echo $stmt."<br/>";
  if ($dbHandle->exec($stmt))
  {
    $stmt = "INSERT INTO transactions (date_time, member_id, transaction_type, amount_cents) SELECT datetime('now', 'localtime'), id, 0, ".$initialDepositCents." FROM members WHERE userid = '".$rfid."'";
    //echo $stmt."<br/>";
    if ($dbHandle->exec($stmt))
    {
      mail($name." <".$email.">", "Welcome to the coffee club", "Your password is: ".$password);
      echo "<p>Addition of member ".$name." completed successfully.</p>";
    }
  }
}

if (array_key_exists("ac", $_POST) && $_POST["ac"] == "member_addition")
{
  member_addition($dbHandle, $_POST["name"], $_POST["username"], $_POST["email"], $_POST["rfid"], $_POST["initial_deposit"]);
}
?>

<p>Add member</p>

<form action="add_member.php" method="post">
<input type="hidden" name="ac" value="member_addition">
<p>Name<br/><input type="text" size="50" name="name" /></p>
<p>Email<br/><input type="text" size="50" name="email" /></p>
<p>Username<br/><input type="text" name="username" /></p>
<p>RFID<br/><input type="text" name="rfid" /></p>
<p>Initial deposit<br/><input type="text" name="initial_deposit" /></p>
<p><input type="submit" name="Submit" /></p>
</form>
