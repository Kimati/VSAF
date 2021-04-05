<form action="amount.php" method="POST">
    Amount <input type='integer' name='amount'><br><br>
    <button type='submit' name="amountsubmit">OK</button>
</form>

<?php
if(isset($_POST['amountsubmit']))
{
    $amount=$_POST['amount'];
    if(!is_numeric($amount))
    {
        echo "Enter valid amount";
    }
    elseif($amount <= 6)
    {
        echo "The amount in transaction is low";
    }
    else{
        header("Location:mpesapinprompt.php");
    }
}
?>