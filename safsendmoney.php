
<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['simcontacts'])) // To handle the Search Sim Contacts button
    {
        echo "<form action='safsendmoney.php' method='POST'>
                Enter Name<br>
                <input type='text' name='name'>
                <button type='submit' name='searchname'>OK</button>
                </form>";
    }
    elseif(isset($_POST['phonenumber'])) //To handle the phone no. button
    {
        echo "<form action='safsendmoney.php' method='POST'>
                Enter phone no.<br>
                <input type='number' name='phone'><br><br>
                <button type='submit' name='phoneno'>OK</button>
                </form>";

    }

    elseif(isset($_POST['phone'])) //phone here is the phone number of the person the normal user member is sending money to.
    {
       $_SESSION['targetNumber'] = $_POST['phone'];
        //$userSendTargetPhone =  $_SESSION['targetNumber'];

        echo "<form action='safsendmoney.php' method='POST'>
                Enter Amount<br>
                <input type='number' name='transferamount'><br><br>
                <button type='submit' name='amount'>OK</button>
              </form>";
    }

    elseif(isset($_POST['transferamount']))
    {
        //global $accbalance;
        //$userAmountToSend = $_POST['transferamount'];
       $_SESSION['userAmountToSend'] = $_POST['transferamount'];
        echo "<form action='safsendmoney.php' method='POST'>
                Enter M-PESA PIN<br>
                <input type='number' name='mpesapin'><br><br>
                <button type='submit' name='mpin'>OK</button>
                </form>";
    }
    elseif(isset($_POST['mpesapin'])) //To handle the phone no. button
    {
        //global $mpesaPin;
       // $userMpesaPin = $_POST['mpesapin'];
        $_SESSION['mpesaPin'] = $_POST['mpesapin'];
        echo "<form action='sendmoneyvalidator.php' method='POST'>
                <h3>Send Money to ------ KSH ----</h3>
                <button type='submit' name='sendcancel'>cancel</button>
                <button type='submit' name='sendok'>OK</button>
                </form>";
    }

}
