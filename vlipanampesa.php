<?php
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['paybill']))
    {
        echo "<form action='vsaflipanampesa.php' method='POST'>
          <button type='submit' name='simcontacts'>Search SIM Contacts</button>
          <button type='submit' name='businessnumber'>Enter business no</button>
    </form>";
    }
    elseif(isset($_POST['buygoods']))
    {
        tillNumber();
    }
    elseif(isset($_POST['businessnumber']))
    {
        businessNumber();
    }
    elseif(isset($_POST['businessno']))
    {
        $_SESSION['businessnumber'] = $_POST['businessno'];
        accNumberPrompt();
    }
    elseif(isset($_POST['accsimcontacts']))
    {
        echo "Hallo, We are not covering this here....";
        die();
    }

    elseif(isset($_POST['accountnumber']))
    {
        accountNumber();
    }

    elseif(isset($_POST['accountno']))
    {
        $_SESSION['accountnumber'] = $_POST['accountno'];
        lipaAmount();
    }

    elseif(isset($_POST['lipaamount']))
    {
        $_SESSION['lipaamount'] = $_POST['lipaamount'];
        mpesaPin();
    }

    elseif(isset($_POST['mpesapin']))
    {
        $_SESSION['mpesapin'] = $_POST['mpesapin'];
        confirmPayment();
    }

}