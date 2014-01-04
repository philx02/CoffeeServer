<script language="javascript">
var DeleteHit = false;
var RadioHit = false;
var PrivilegeHit = false;
function ConfirmAction()
{
  if (RadioHit)
  {
    if (DeleteHit)
    {
      if (confirm("Are you sure to delete this member's account?"))
      {
        return true;
      }
      else
      {
        DeleteHit = false;
        PrivilegeHit = false;
        return false;
      }
    }
    else if (PrivilegeHit)
    {
      return true;
    }
    else
    {
      DeleteHit = false;
      PrivilegeHit = false;
      return false;
    }
  }
  else
  {
    alert("No member selected.");
    DeleteHit = false;
    PrivilegeHit = false;
    return false;
  }
}
function DeleteClick()
{
  DeleteHit = true;
}
function RadioClick()
{
  RadioHit = true;
}
function PrivilegeClick()
{
  PrivilegeHit = true;
}
</script>

<?php
include("header.php");
$dbHandle = new CoffeeDb();

function grant_admin($dbHandle, $memberId)
{
  $stmt = "SELECT name, balance_cents FROM members WHERE id = ".$memberId;
  $name = $dbHandle->querySingle($stmt);
  $stmt = "UPDATE members SET admin = 1 WHERE id = ".$memberId;
  if ($dbHandle->exec($stmt))
  {
    echo "<p>Admin privilege grant for ".$name." completed successfully.</p>";
  }
}

function revoke_admin($dbHandle, $memberId)
{
  $stmt = "SELECT name, balance_cents FROM members WHERE id = ".$memberId;
  $name = $dbHandle->querySingle($stmt);
  $stmt = "UPDATE members SET admin = 0 WHERE id = ".$memberId;
  if ($dbHandle->exec($stmt))
  {
    echo "<p>Admin privilege revokation for ".$name." completed successfully.</p>";
  }
}

function delete_member($dbHandle, $memberId)
{
  $stmt = "SELECT name, balance_cents FROM members WHERE id = ".$memberId;
  $result = $dbHandle->querySingle($stmt, true);
  $stmt = "DELETE FROM members WHERE id = ".$memberId;
  if ($dbHandle->exec($stmt))
  {
    echo "<p>Deletion of ".$result["name"]."'s account completed successfully. The balance was ".number_format((float)abs($result["balance_cents"]/100), 2)."$.</p>";
  }
}

if (array_key_exists("grant_privilege", $_POST))
{
  grant_admin($dbHandle, $_POST["memberid"]);
}
else if (array_key_exists("revoke_privilege", $_POST))
{
  revoke_admin($dbHandle, $_POST["memberid"]);
}
else if (array_key_exists("delete_member", $_POST))
{
  delete_member($dbHandle, $_POST["memberid"]);
}
?>

<p>Members management</p>

<a href="add_member.php">Add member</a>
<form action="manage_members.php" onSubmit="return ConfirmAction()"  method="post">
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
  echo "<td><input onClick=\"return RadioClick()\" type='radio' name='memberid' value='".$row["id"]."' /></td>";
  echo "<td>".$row["name"]."</td>";
  echo "<td>".number_format((float)$row["balance_cents"]/100, 2)."</td>";
  echo "<td>".$row["admin"]."</td>";
  echo "</tr>";
}
?>
</table>
<input type="submit" onClick="return PrivilegeClick()" name="grant_privilege" value="Grant admin privilege"/>
<input type="submit" onClick="return PrivilegeClick()" name="revoke_privilege" value="Revoke admin privilege"/>
<input type="submit" onClick="return DeleteClick()" name="delete_member" value="Delete"/>
</form>
