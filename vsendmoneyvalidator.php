<?php
session_start();

include('dbconnection.php');


function vUserSendMoneyStatus()
{
//Creating a variable to hold the normal saf user send money transaction status

    if (isset($_SESSION['vSendMoneyStatus']))
    {
        $vLifeTime = 180;
        $vElapsedTime = time() - $_SESSION['vSendMoneyStatus'];
        if ($vElapsedTime < $vLifeTime)
        {
            echo "We could not process your request because there is another transaction in progress in your account.
Please wait.
Safaricom the Better Option.<br>";
        }
        elseif ($vElapsedTime > $vLifeTime)
        {
            unset($_SESSION['vSendMoneyStatus']);
        }

    }

    //If the user has not yet sent any money virtually.
    else
     {
         if(isset($_SESSION['userTransactionStatus']))
         {
             $lifeTime = 180;
             $userStartTime = $_SESSION['userTransactionStatus'];
             $elapsedTime = time() - $userStartTime;

             if ($elapsedTime < $lifeTime)
             {
                 echo "Dear Customer, your real safaricom account is processing a transaction. Please wait for some time. Safaricom is for 
            you.";
                 die();
             }
             elseif ($elapsedTime > $lifeTime)
             {
                 unset($_SESSION['userTransactionStatus']);
             }
         }

                $_SESSION['vSendMoneyStatus'] = time();

                global $conn;

                //Retrieving the phone number of the current virtual user from the session
                $vSenderNumber = $_SESSION['virtualSenderNumber'];

                $amountTransfered = $_SESSION['vUserAmountToSend'];
                $senderMpesaPin = $_SESSION['vUserMpesaPin'];


                //Finding credentials of the virtual money sender from the database based on their phone number.
                $queryVSenderPhone = "select * from customers where PhoneNumber = '$vSenderNumber' limit 1";
                $queryVSenderPhoneresult = mysqli_query($conn, $queryVSenderPhone);

                if ($queryVSenderPhoneresult)
                {
                    if (mysqli_num_rows($queryVSenderPhoneresult) > 0)
                    {
                        $senderData = mysqli_fetch_assoc($queryVSenderPhoneresult);
                        if ($amountTransfered >= $senderData['AccBalance'])
                        {
                            echo "Failed. Insufficient funds in your M-PESA account as well as Fuliza M-PESA to pay " . $amountTransfered .
                                ".Your Mpesa balance is " . $senderData['AccBalance'] . ".Available Fuliza
                            M-PESA limit is KSHS. 0";
                        } elseif ($senderMpesaPin !== $senderData['Mpesapin'])
                        {
                            echo "You have entered the wrong PIN. Please try again.";
                        }
                      else
                            {
                            global $queryphonearray;
                            $remainingBalance = $senderData['AccBalance'] - $amountTransfered;
                            echo "POA08J5A62 Confirmed " . $amountTransfered . " sent to " . $queryphonearray['Name'] . " " . $queryphonearray['PhoneNumber'] . " on
                      " . date('d-m-y h:i:s') . ". New M-PESA balance is Ksh" . $remainingBalance . ". Transaction cost, xxx. Amount you can 
                       transact within the day is 299,890.00. Protect your PIN to secure your money. To reverse,forward this message to 456.";

                            $updateSenderBalance = "UPDATE customers SET AccBalance=$remainingBalance WHERE PhoneNumber=$vSenderNumber";
                            $newSenderBalance = mysqli_query($conn, $updateSenderBalance);

                            $amountCredited = $_SESSION['vUserAmountToSend'];
                            $receiverInitBalance = $queryphonearray['AccBalance'];
                            $receiverPhone = $queryphonearray['PhoneNumber'];
                            $receiverNewBalance = $receiverInitBalance + $amountCredited;
                            $receiverUpdateBalance = "UPDATE customers SET AccBalance=$receiverNewBalance WHERE PhoneNumber=$receiverPhone";
                            $receiverUpdatedBalance = mysqli_query($conn, $receiverUpdateBalance);


                }
            }
        }

    }

}


if($_SERVER['REQUEST_METHOD'] = 'POST')
{
    if(isset($_POST['sendcancel']))
    {
        header("Location:proceed.php");
    }

    elseif(isset($_POST['sendok']))
    {
        global $conn;
        $userSendTargetPhone= $_SESSION['vUserTargetNo']; //The phone of the person the virtual user is sending money to.


        $queryphone = "select * from customers where PhoneNumber = '$userSendTargetPhone' limit 1";
        $queryphoneresult = mysqli_query($conn,$queryphone);

        if($queryphoneresult)
        {
            if(mysqli_num_rows($queryphoneresult) > 0)
            {
                $queryphonearray = mysqli_fetch_assoc($queryphoneresult);
                $receiverBalance = $queryphonearray['AccBalance'];
                $receiverPhone = $queryphonearray['PhoneNumber'];

                //checking if the target number matches any number in the table of registered safaricom number
                if($userSendTargetPhone !==  $queryphonearray['PhoneNumber'])
                {
                    echo "Invalid phone number, No customer registered with that number!";
                }
                else
                {
                    vUserSendMoneyStatus();


                }
            }
        }


    }
}




