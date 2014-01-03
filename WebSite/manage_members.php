<?php
include("header.php");
$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);

function member_addition($dbHandle, $name, $username, $rfid, $initialDeposit)
{
  $email = $username."@cae.com";
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
      echo "<p>Addition of user ".$name." completed successfully.</p>";
    }
  }
}

if (array_key_exists("ac", $_POST) && $_POST["ac"] == "member_addition")
{
  member_addition($dbHandle, $_POST["name"], $_POST["username"], $_POST["rfid"], $_POST["initial_deposit"]);
}
?>

<p>Members management</p>

<hr>

<p>Add member</p>

<form action="manage_members.php" method="post">
<input type="hidden" name="ac" value="member_addition">
<table>
<tr>
<td>Name</td>
<td>Username</td>
<td>RFID</td>
<td>Initial deposit</td>
<td>&nbsp;</td>
</tr>
<tr>
<td><input type="text" name="name" /></td>
<td><input type="text" name="username" /></td>
<td><input type="text" name="rfid" /></td>
<td><input type="text" name="initial_deposit" /></td>
<td><input type="submit" name="Submit" /></td>
</tr>
</table>
</form>

<hr>

<table border="1">
<tr>
<th width="25">&nbsp;</th>
<th width="300">Name</th>
<th width="60">Balance</th>
<th width="60">Admin</th>
</tr>
<?php
$stmt = "SELECT id, name, balance_cents, admin FROM members";
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<tr>";
  echo "<td><input type='radio' name='memberid' value='".$row["id"]."' /></td>";
  echo "<td>".$row["name"]."</td>";
  echo "<td>".number_format((float)abs($row["balance_cents"]/100), 2)."</td>";
  echo "<td>".$row["admin"]."</td>";
  echo "</tr>";
}
?>
</table>
