<?php
session_start();

include('dbconnection.php');

//Function to validate the payer credentials.
function checkPayerCred()
{
    //Check if there is another lipa na mpesa transaction in progresss
    $lipalifetime = 180;
    if (isset($_SESSION['lipanampesa']))
    {

        $lipaelapsedtime = time() - $_SESSION['lipanampesa'];
        if ($lipaelapsedtime <= $lipalifetime)
        {
            echo "We could not process your request because there is another transaction in progress in your account. 
            Please wait. 
            Safaricom the Better Option.<br>";
        }
        elseif ($lipaelapsedtime > $lipalifetime)
        {
            unset($_SESSION['lipanampesa']);
        }

    }

    //If no lipa na mpesa transaction in progress
    else
        {
        //check if There is lipa na mpesa transaction progressing  in the virtual mode

        if (isset($_SESSION['vlipanampesa']))
        {

            $vlipalifetime = 180;
            $vlipastarttime = $_SESSION['vlipanampesa'];
            $vlipaelapsedtime = time() - $vlipastarttime;

            if ($vlipaelapsedtime < $vlipalifetime)
            {
                echo "Dear Customer, your Virtual safaricom account is processing a transaction. Please wait for some time. Safaricom is for 
            you.";
                die();
            }
            elseif ($vlipaelapsedtime > $vlipalifetime)
            {
                unset($_SESSION['vlipanampesa']);
            }
        }


        $_SESSION['lipanampesa'] = time();
        global $conn;
        global $receiverAccNo;
        global $receiverBsNo;
        //global $businessData;
        $senderNumber = $_SESSION['activenormaluser'];

        $amountTransfered = $_SESSION['lipaamount'];
        $senderMpesaPin = $_SESSION['mpesapin'];

        //Finding credentials of the normal money sender from the database based on their phone number.
        $querySenderPhone = "select * from customers where PhoneNumber = '$senderNumber' limit 1"; //To find the phone number of the money payer
        $querySenderPhoneresult = mysqli_query($conn, $querySenderPhone);
        if ($querySenderPhoneresult)
        {
            if (mysqli_num_rows($querySenderPhoneresult) > 0)
            {
                $senderData = mysqli_fetch_assoc($querySenderPhoneresult);
                if ($amountTransfered >= $senderData['AccBalance'])
                {
                    echo "Failed. Insufficient funds in your M-PESA account as well as Fuliza M-PESA to pay " . $amountTransfered .
                        ".Your Mpesa balance is " . $senderData['AccBalance'] . ".Available Fuliza
                            M-PESA limit is KSHS. 0";
                    die();
                }
                elseif ($senderMpesaPin !== $senderData['Mpesapin'])
                {
                    echo "You have entered the wrong PIN. Please try again.";
                    die();
                }
                else
                    {
                    global $businessData;
                    $remainingBalance = $senderData['AccBalance'] - $amountTransfered;

                    echo "POA08J5A67 Confirmed. Kshs." . $amountTransfered . " sent to " . $receiverAccNo . " for account " . $receiverAccNo .
                        " on " . date('d-m-y h:i:s') . "at " . time() . " New M-PESA balance Kshs." . $remainingBalance . ". Transaction cost, xxx. Amount you can 
                       transact within the day is 299,890.00. Protect your PIN to secure your money. For reversal contact, send this sms to 456.";

                    $updatePayerBalance = "UPDATE customers SET AccBalance=$remainingBalance WHERE PhoneNumber=$senderNumber";
                    $newPayerBalance = mysqli_query($conn, $updatePayerBalance);

                    //Update receiver business balance
                    //$amountCredited = $amountTransfered;
                    $receiverInitBalance = $businessData['AccBalance'];
                    $receiverNewBalance = $receiverInitBalance + $amountTransfered;
                    $receiverUpdateBalance = "UPDATE registered_business_numbers SET AccBalance=$receiverNewBalance WHERE BusinessNumber=$receiverBsNo";
                    $receiverUpdatedBalance = mysqli_query($conn, $receiverUpdateBalance);
                       die();

                }

            }


        }
    }

}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['abort'])) {
        header("Location: Home.php");
    }
    elseif(isset($_POST['confirm']))
    {
        global $conn;
        //Checking if the business number is registered.
        $bsnumber = $_SESSION['businessnumber']; //Retrievve the business number from the session.
        $query = "SELECT * FROM registered_business_numbers WHERE BusinessNumber = '$bsnumber' limit 1";
        $queryresults = mysqli_query($conn,$query);

        if( $queryresults)
        {
            if(mysqli_num_rows($queryresults) > 0)
            {
                $businessData = mysqli_fetch_assoc($queryresults);
                $receiverBsNo = $businessData['BusinessNumber'];
                $receiverAccNo = $businessData['AccountNumber'];
                $receiverBalance = $businessData['AccBalance'];
                if($bsnumber !== $receiverBsNo)
                {
                    echo "Invalid Business Number. That is not a registered business number. Please Try again.";
                    die();
                }
                else
                    {

                        checkPayerCred();
                }

            }

        }



    }
}