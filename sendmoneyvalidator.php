<?php
session_start();

include('dbconnection.php');

function checkSenderCred()
{

    $lifeTime = 180;
    if(isset($_SESSION['userTransactionStatus']))
    {

        $elapsedTime = time() - $_SESSION['userTransactionStatus'];
        if($elapsedTime < $lifeTime)
        {
            echo "We could not process your request because there is another transaction in progress in your account. 
            Please wait. 
            Safaricom the Better Option.<br>";
        }

        elseif($elapsedTime > $lifeTime)
        {
            unset($_SESSION['userTransactionStatus']) ;
        }

    }

    //If no transaction is in progress
    else
    {
        //checkVuserStatus();
        if(isset( $_SESSION['vSendMoneyStatus']))
        {

            $vlifetime = 180;
            $vstarttime = $_SESSION['vSendMoneyStatus'];
            $velapsedtime = time() - $vstarttime;

            if ($velapsedtime < $vlifetime) 
            {
                echo "Dear Customer, your Virtual safaricom account is processing a transaction. Please wait for some time. Safaricom is for 
            you.";
                die();
            }
            elseif ($velapsedtime > $vlifetime)
            {
                unset($_SESSION['vSendMoneyStatus']);
            }
        }


        $_SESSION['userTransactionStatus'] = time();
//The following code belongs o the "Copy here section below"
        global $conn;

        $senderNumber = $_SESSION['activenormaluser'];

        $amountTransfered = $_SESSION['userAmountToSend'];
        $senderMpesaPin =  $_SESSION['mpesaPin'];

        //Finding credentials of the normal money sender from the database based on their phone number.
        $querySenderPhone = "select * from customers where PhoneNumber = '$senderNumber' limit 1"; //To find the phone number of the money sender
        $querySenderPhoneresult = mysqli_query($conn, $querySenderPhone);

        if ($querySenderPhoneresult) {
            if (mysqli_num_rows($querySenderPhoneresult) > 0) {
                $senderData = mysqli_fetch_assoc($querySenderPhoneresult);
                if ($amountTransfered >= $senderData['AccBalance']) {
                    echo "Failed. Insufficient funds in your M-PESA account as well as Fuliza M-PESA to pay " . $amountTransfered .
                        ".Your Mpesa balance is " . $senderData['AccBalance'] . ".Available Fuliza
                            M-PESA limit is KSHS. 0";
                }
                elseif ($senderMpesaPin !== $senderData['Mpesapin'])
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

                    $updateSenderBalance = "UPDATE customers SET AccBalance=$remainingBalance WHERE PhoneNumber=$senderNumber";
                    $newSenderBalance = mysqli_query($conn, $updateSenderBalance);

                    $amountCredited = $_SESSION['userAmountToSend'];
                    $receiverInitBalance = $queryphonearray['AccBalance'];
                    $receiverPhone = $queryphonearray['PhoneNumber'];
                    $receiverNewBalance = $receiverInitBalance + $amountCredited;
                    $receiverUpdateBalance = "UPDATE customers SET AccBalance=$receiverNewBalance WHERE PhoneNumber=$receiverPhone";
                    $receiverUpdatedBalance = mysqli_query($conn, $receiverUpdateBalance);

                    //Save the transaction to the transaction table.
                    $sendMonTrans = "Send Money";
                    $receiveMonTrans = "Received Money";

                    $transactionAmount = $_SESSION['userAmountToSend'];

                    $sender = $_SESSION['activenormaluser'];
                    $receiver = $queryphonearray['PhoneNumber'];

                    //Creating two queries to save the transaction data in the transactions table
                    $creditTrans = "INSERT INTO transactions(transaction_type,user_phone,debit,credit) VALUES('$sendMonTrans',' $sender','-','$transactionAmount')";

                  $debitTrans = "INSERT INTO transactions(transaction_type,user_phone,debit,credit) VALUES('$receiveMonTrans','$receiver','$transactionAmount','-')";

                //Executing the above two queries
                  $execcreditTrans = mysqli_query($conn,$creditTrans);
                  $execdebitTrans = mysqli_query($conn,$debitTrans);
                
                 header("Location:selectcard.php");




                }

            }


}
}


}


if($_SERVER['REQUEST_METHOD'] = 'POST')
{

    if(isset($_POST['sendcancel']))
    {
    header("Location:safusermenu.php");
    }

    elseif(isset($_POST['sendok']))
    {
//echo "Hallo";
        global $conn;
        $userSendTargetPhone= $_SESSION['targetNumber'];


          $queryphone = "select * from customers where PhoneNumber = '$userSendTargetPhone' limit 1";
          $queryphoneresult = mysqli_query($conn,$queryphone);

          if($queryphoneresult)
          {
              if(mysqli_num_rows($queryphoneresult) > 0)
              {
                  $queryphonearray = mysqli_fetch_assoc($queryphoneresult);
                  $receiverBalance = $queryphonearray['AccBalance'];
                  $receiverPhone = $queryphonearray['PhoneNumber'];
                 // $receiverId = $queryphonearray['NationalIdentity'];

                  //checking if the target number matches any number in the table of registered safaricom number
                  if($userSendTargetPhone !==  $queryphonearray['PhoneNumber'])

                  {
                      echo "Invalid phone number, No customer registered with that number!";
                  }

                  //Checking if the money receiver number entered is the same as the money sender number
                  elseif($userSendTargetPhone === $_SESSION['activenormaluser'])
                  {
                      echo "Dear Customer, You can't send money to your own account!";
                  }

                 else
                 {
                      checkSenderCred();
                  }
              }
          }


        }
}




