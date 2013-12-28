<?php
include("header.php");
?>

<p>Member's transaction history</p>

<form action="transaction_history.php" target="history_frame" method="get">
<table>
<tr>
<td>Name</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>
<select name="memberid">
<?php
$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT id, name FROM members";
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
}
?>
</select>
</td>
<td><input type="submit" value="Submit"></td>
</tr>
</table>
</form>

<iframe name="history_frame" frameborder="0" scrolling="auto" width=900 height=800 marginwidth=5 marginheight=5></iframe>
