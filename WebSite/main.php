<?php
include("header.php");
?>

<div align="center">
<table border="1" width=1200>
<tr>
<td width=* colspan=2>
<?php
$dbHandle = new SQLite3('test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT balance_cents FROM members WHERE id = ".$_SESSION["id_logged"];
echo $stmt."<br/>";
$result = $dbHandle->query($stmt);
echo "Current balance: ".($result->fetchArray()["balance_cents"]/100)."$.";
?>
</td>
</tr>
<tr>
<td width=100 valign="top">
<p><a href="transaction_history.php" target="inlineframe">Transaction History</a></p>
<p><a href="member_deposit.php" target="inlineframe">Member Deposit</a></p>
</td>
<td>
<iframe name="inlineframe" src="transaction_history.php" frameborder="0" scrolling="auto" width=1100 height=1000 marginwidth=5 marginheight=5></iframe> 
</td>
</tr>
</table>
</div>
