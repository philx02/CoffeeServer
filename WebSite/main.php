<?php
include("header.php");
?>

<div align="center">
<table border="1" width="100%">
<tr>
<td width=* colspan=2>
<?php
$dbHandle = new CoffeeDb();
$stmt = "SELECT balance_cents FROM members WHERE id = ".$_SESSION["id_logged"];
$userBalanceCents = $dbHandle->querySingle($stmt);
$stmt = "SELECT sum(balance_cents) FROM members";
$workingCapitalCents = $dbHandle->querySingle($stmt);
?>
<table width="100%">
<tr>
<td align="left">Current balance: <?php echo number_format((float)($userBalanceCents/100), 2); ?>$</td>
<td align="right">Working capital: <?php echo number_format((float)($workingCapitalCents/100), 2); ?>$</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="150" valign="top">
<table>
<p><a href="transaction_history.php?<?php echo "memberid=".$_SESSION["id_logged"]; ?>" target="inlineframe">Transaction History</a></p>
<p><a href="change_password.php" target="inlineframe">Change Password</a></p>
<p><a href="logout.php">Logout</a></p>
<?php
if (array_key_exists("admin", $_SESSION) && $_SESSION["admin"] == 1)
{
  echo <<<EOT
<hr/>
<p><a href="add_member.php" target="inlineframe">Add Member</a></p>
<p><a href="manage_members.php" target="inlineframe">Manage Members</a></p>
<p><a href="member_deposit.php" target="inlineframe">Member Deposit</a></p>
<p><a href="member_transaction_history.php" target="inlineframe">Member's Transaction History</a></p>
EOT;
}
?>
</table>
</td>
<td>
<iframe name="inlineframe" src="transaction_history.php?<?php echo "memberid=".$_SESSION["id_logged"]; ?>" frameborder="0" scrolling="auto" width="100%" height="1000" marginwidth=5 marginheight=5></iframe> 
</td>
</tr>
</table>
</div>
