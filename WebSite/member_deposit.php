<?php
include("header.php");

$dbHandle = new SQLite3('test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT id, name FROM members";
?>

<select name="member">
<?php 
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<option value=\"".$row['id']."\">".$row['name']."</option>\n";
}
?>
</select>
