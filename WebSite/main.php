<?php
include("header.php");
?>

<div align="center">
<table border="1" width="1200">
<tr>
<td width=* colspan=2>
<?php
$dbHandle = new SQLite3('../coffeedb/test.db', SQLITE3_OPEN_READWRITE);
$stmt = "SELECT balance_cents FROM members WHERE id = ".$_SESSION["id_logged"];
$result = $dbHandle->querySingle($stmt);
echo "Current balance: ".number_format((float)($result/100), 2)."$";
?>
</td>
</tr>
<tr>
<td width="150" valign="top">
<p><a href="transaction_history.php?<?php echo "memberid=".$_SESSION["id_logged"]; ?>" target="inlineframe">Transaction History</a></p>
<p><a href="logout.php">Logout</a></p>
<hr>
<?php
if (array_key_exists("admin", $_SESSION) && $_SESSION["admin"] == 1)
{
  echo <<<EOT
<p><a href="manage_members.php" target="inlineframe">Manage Members</a></p>
<p><a href="member_deposit.php" target="inlineframe">Member Deposit</a></p>
<p><a href="member_transaction_history.php" target="inlineframe">Member's Transaction History</a></p>
EOT;
}
?>
</td>
<td>
<iframe name="inlineframe" src="transaction_history.php?<?php echo "memberid=".$_SESSION["id_logged"]; ?>" frameborder="0" scrolling="auto" width=1050 height=1000 marginwidth=5 marginheight=5></iframe> 
</td>
</tr>
</table>
</div>
