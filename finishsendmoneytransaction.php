<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(isset($_POST['cancel']))
    {
        header("Location:safusermenu.php");
    }
    elseif(isset($_POST['OK']))
    {
        header("Location:commitsendtransaction.php");
    }
}
?>