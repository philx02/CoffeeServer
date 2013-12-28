<?php
include("header.php");
?>

<p>Members management</p>

<table border="1">
<tr>
<th width="20">&nbsp;</th>
<th width="300">Name</th>
<th width="60">Balance</th>
<th width="60">Admin</th>
</tr>
<?php
$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT id, name, balance_cents, admin FROM members";
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<tr>";
  echo "<td>&nbsp;</td>";
  echo "<td>".$row["name"]."</td>";
  echo "<td>".number_format((float)abs($row["balance_cents"]/100), 2)."</td>";
  echo "<td>".$row["admin"]."</td>";
  echo "</tr>";
}
?>
