<?php
session_start();

include('dbconnection.php');

//Function to validate the payer credentials.
function checkPayerCred()
{
    //Check if there is another Virtual lipa na mpesa transaction in progresss
    $vlipalifetime = 180;
    if (isset($_SESSION['vlipanampesa']))
    {

        $vlipaelapsedtime = time() - $_SESSION['vlipanampesa'];
        if ($vlipaelapsedtime <= $vlipalifetime)
        {
            echo "We could not process your request because there is another transaction in progress in your account. 
            Please wait. 
            Safaricom the Better Option.<br>";
        }
        elseif ($vlipaelapsedtime > $vlipalifetime)
        {
            unset($_SESSION['vlipanampesa']);
        }

    }

    //If no Virtual lipa na mpesa transaction in progress
    else
    {
        //check if There is lipa na mpesa transaction progressing  in the Normal User Mode.
        if (isset($_SESSION['lipanampesa']))
        {

            $lipalifetime = 180;
            $lipastarttime = $_SESSION['lipanampesa'];
            $lipaelapsedtime = time() - $lipastarttime;

            if ($lipaelapsedtime < $lipalifetime)
            {
                echo "Dear Customer, your Normal safaricom account is processing a transaction. Please wait for some time. Safaricom is for 
            you.";
                die();
            }
            elseif ($lipaelapsedtime > $lipalifetime)
            {
                unset($_SESSION['lipanampesa']);
            }
        }


        $_SESSION['vlipanampesa'] = time();
        global $conn;
        global $targetAccNo;
        global $targetBsNo;


        $vSenderNumber = $_SESSION['virtualSenderNumber'];
        $amountTransfered = $_SESSION['vlipaamount'];
        $senderMpesaPin = $_SESSION['vmpesapin'];

        //Finding credentials of the Virtual money sender from the database based on their phone number.
        $querySenderPhone = "select * from customers where PhoneNumber = '$vSenderNumber' limit 1"; //To find the phone number of the money payer
        $querySenderPhoneresult = mysqli_query($conn, $querySenderPhone);
        if ($querySenderPhoneresult)
        {
            if (mysqli_num_rows($querySenderPhoneresult) > 0)
            {
                $vSenderData = mysqli_fetch_assoc($querySenderPhoneresult);
                if ($amountTransfered >= $vSenderData['AccBalance'])
                {
                    echo "Failed. Insufficient funds in your M-PESA account as well as Fuliza M-PESA to pay " . $amountTransfered .
                        ".Your Mpesa balance is " . $vSenderData['AccBalance'] . ".Available Fuliza
                            M-PESA limit is KSHS. 0";
                    die();
                }
                elseif ($senderMpesaPin !== $vSenderData['Mpesapin'])
                {
                    echo "You have entered the wrong PIN. Please try again.";
                    die();
                }
                else
                {
                    global $targetBsData;
                    $remainingBalance = $vSenderData['AccBalance'] - $amountTransfered;

                    echo "POA08J5A67 Confirmed. Kshs." . $amountTransfered . " sent to " . $targetAccNo . " for account " . $targetAccNo .
                        " on " . date('d-m-y h:i:s') . "at " . time() . " New M-PESA balance Kshs." . $remainingBalance . ". Transaction cost, xxx. Amount you can 
                       transact within the day is 299,890.00. Protect your PIN to secure your money. For reversal contact, send this sms to 456.";

                    $updatePayerBalance = "UPDATE customers SET AccBalance=$remainingBalance WHERE PhoneNumber=$vSenderNumber";
                    $newPayerBalance = mysqli_query($conn, $updatePayerBalance);

                    //Update receiver business balance
                    //$amountCredited = $amountTransfered;
                    $targetInitBalance = $targetBsData['AccBalance'];
                    $targetNewBalance = $targetInitBalance + $amountTransfered;
                    $targetUpdateBalance = "UPDATE registered_business_numbers SET AccBalance=$targetNewBalance WHERE BusinessNumber=$targetBsNo";
                    $receiverUpdatedBalance = mysqli_query($conn, $targetUpdateBalance);
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
        $bsnumber = $_SESSION['vbusinessnumber']; //Retrievve the business number from the session.
        $query = "SELECT * FROM registered_business_numbers WHERE BusinessNumber = '$bsnumber' limit 1";
        $queryresults = mysqli_query($conn,$query);

        if( $queryresults)
        {
            if(mysqli_num_rows($queryresults) > 0)
            {
                $targetBsData = mysqli_fetch_assoc($queryresults);
                $targetBsNo = $targetBsData['BusinessNumber'];
                $targetAccNo = $targetBsData['AccountNumber'];
                $targetBalance = $targetBsData['AccBalance'];
                if($bsnumber !== $targetBsNo)
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