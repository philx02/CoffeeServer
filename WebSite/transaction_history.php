<?php
include("header.php");

$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT name FROM members WHERE id = ".$_GET["id"];
$result = $dbHandle->querySingle($stmt);
?>

<p>Transaction history for user <?php echo $result ?></p>

<table border="1">
<tr>
<th>Time</th>
<th>Type</th>
<th>Amount</th>
<th>Balance</th>
</tr>
<?php
$stmt = "SELECT balance_cents FROM members WHERE id = ".$_GET["id"];
$balance_cents = $dbHandle->querySingle($stmt);
$stmt = "SELECT date_time, (SELECT name FROM transactions_name WHERE transactions_name.transaction_type = transactions.transaction_type) as transaction_name, amount_cents FROM transactions WHERE member_id = ".$_GET["id"]." ORDER BY date_time DESC";
$result = $dbHandle->query($stmt);
while ($row = $result->fetchArray())
{
  echo "<tr>";
  echo "<td>".$row["date_time"]."</td>";
  echo "<td>".$row["transaction_name"]."</td>";
  echo "<td>".number_format((float)abs($row["amount_cents"]/100), 2)."</td>";
  echo "<td>".number_format((float)$balance_cents/100, 2)."</td>";
  $balance_cents -= $row["amount_cents"];
  echo "</tr>";
}
?>
