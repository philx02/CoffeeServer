<?php
include("header.php");

$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);

if (array_key_exists("ac", $_POST))
{
  if ($_POST["ac"] == "member_deposit")
  {
    if (is_numeric($_POST["amount"]))
    {
      $amount_cents = floor($_POST["amount"] * 100);
      $stmt = "UPDATE members SET balance_cents = balance_cents + ".$amount_cents." WHERE id = ".$_POST["memberid"];
      //echo $stmt."<br/>";
      if ($dbHandle->exec($stmt))
      {
        $stmt = "INSERT INTO transactions (date_time, member_id, transaction_type, amount_cents) VALUES (datetime('now', 'localtime'), ".$_POST["memberid"].", 0, ".$amount_cents.")";
        //echo $stmt."<br/>";
        if ($dbHandle->exec($stmt))
        {
          $stmt = "SELECT balance_cents FROM members WHERE id = ".$_POST["memberid"];
          $result = $dbHandle->querySingle($stmt);
          echo "<p>Deposit of ".$_POST["amount"]."$ completed successfully, new balance is ".number_format((float)($result/100), 2)."$.</p>";
        }
      }
    }
    else
    {
      echo "<p>Invalid value for deposit amount (".$_POST["amount"].").</p>";
    }
  }
  else
  {
    echo "<p>Wrong post action.</p>";
  }
}
?>

<p>Member deposit</p>

<form action="member_deposit.php" method="post">
<input type="hidden" name="ac" value="member_deposit">
<table>
<tr>
<td>Name</td>
<td>Amount</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<select name="memberid">
<?php
$stmt = "SELECT id, name FROM members";
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
}
?>
</select>
</td>
<td><input type="text" name="amount"/></td>
<td><input type="submit" value="Submit"></td>
</tr>
</table>
</form>
