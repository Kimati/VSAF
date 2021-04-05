<form action="sendmoneyprompt.php" method="POST">
    <button type="submit" name="simcontacts" onclick="location.href='Home.php'">Enter Sim Contacts</button><br><br>
    <button type="submit" name="phonenumber">Enter Phone number</button>
</form>
<?php

// function sendmoney()
     // {
        if(isset($_POST['phonenumber']))
        {
            header("Location:checksendmoney.php");
            //header("Location:scripts.php");
        }

        elseif(isset($_POST['simcontacts']))  //if Enter Sim Contacts button is selected
        {
            header("Location:Home.php");
        }


?>

