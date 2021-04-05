<html>
<form action="checksendmoney.php" method="POST">
Enter Phone Number <input type="text" name="receiverphone" required><br><br>
<button type="submit">OK</button>
</form>
</html>

<?php
if(isset($_POST['receiverphone']))
{
    $name = $_POST['receiverphone'];
    if(!is_numeric($name))
    {
        //header("Location:checksendmoney.php");
        echo "Please enter a valid phone number";
    }
    else
    {
        //echo "Continue";
       /*echo "<form action='' method='POST'>
                Amount <input type='integer' name='amount'><br><br>
                <button type='submit'>OK</button>
               </form>";
       */
        header("Location:amount.php");
    }
}
