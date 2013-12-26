<?php
include("header.php");

$dbHandle = new SQLite3('test.db', SQLITE3_OPEN_READWRITE);

if (array_key_exists("ac", $_POST))
{
  if ($_POST["ac"] == "member_deposit")
  {
    $amount_cents = floor($_POST["amount"] * 100);
    $stmt = "UPDATE members SET balance_cents = balance_cents + ".$amount_cents." WHERE userid = ".$_POST["memberid"];
    echo $stmt."<br/>";
  }
  else
  {
    echo "<p>Wrong post action.</p>";
  }
}
?>

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
