<?php

include('dbconnection.php');


function vsendMoney()
{
    //$virtualSenderNumber =  $virtualSenderNumber; //The $vuserno variable is declared in proceed.php file and it is the phone number of the virtual user entered
    echo "<form action='vsafsendmoney.php' method='POST'>
         <button type='submit' name='simcontacts'>Search SIM Contacts</button><br><br>
         <button type='submit' name='phonenumber'>Enter phone no.</button>
         </form>";
}

function vLipaNaMpesa()
{
    //$virtualSenderNumber =  $virtualSenderNumber; //The $vuserno variable is declared in proceed.php file and it is the phone number of the virtual user entered
    echo "<form action='vsaflipanampesa.php' method='POST'>
         <button type='submit' name='paybill'>Pay Bill</button><br><br>
         <button type='submit' name='buygoods'>Buy Goods and Services</button>
         </form>";


}

function vwithdrawMoney()
{
    echo "<form action='safuser.php' method='POST'>
                    <button type='submit' name='Agent'>From Agent</button><br><br>
                     <button type='submit' name='ATM'>From ATM</button>
                 </form>";

}

function fromAgent()
{
    echo "<form action='safuser.php' method='POST'>
                    Enter agent no.<br><input type='number' name='agentno'>
                    <button type='submit' name='withdrawfromagent'>OK</button>
                    
                 </form>";
}

function fromAtm()
{
    echo "<form action='safuser.php' method='POST'>
                    Enter agent no.<br><input type='number' name='atmno'>
                    <button type='submit' name='withdrawfromatm'>OK</button>
                    
                 </form>";
}

function fromAgentAmount()
{
    echo "<form action='safuser.php' method='POST'>
                    Amount<br><input type='number' name='fromagentamount'>
                    <button type='submit' name='withdrawfromagentamount'>OK</button>
                    
                 </form>";
}

function fromAtmAmount()
{
    echo "<form action='safuser.php' method='POST'>
                    Amount<br><input type='number' name='fromatmamount'>
                    <button type='submit' name='withdrawfromatmamount'>OK</button>
                    
                 </form>";
}

function agentMpesaPin()
{
    echo "<form action='safuser.php' method='POST'>
                    Mpesa Pin<br><input type='number' name='mpin'>
                    <button type='submit'>OK</button>
                    
                 </form>";
}

function atmMpesaPin()
{
    echo "<form action='safuser.php' method='POST'>
                    Mpesa Pin<br><input type='number' name='mpin'>
                    <button type='submit' name='atmmpesapin'>OK</button>
                    
                 </form>";
}

function validateagentmpesapin()
{
    global $conn;
    $mpesapin = $_POST['mpin'];
    $mpesapinquery = "select * from customers where Mpesapin = '$mpesapin' limit 1";
    $res = mysqli_query($conn,$mpesapinquery);

    if(empty($mpesapin))
    {
        echo "Please Enter your mpesa pin";
    }
    elseif (!is_numeric($mpesapin))
    {
        echo "Mpesa pin cannot contain alphabetical letters!";
    }
    else{
        if($res)
        {
            if(mysqli_num_rows($res) > 0)
            {
                $usercred = mysqli_fetch_assoc($res);
                if($mpesapin !== $usercred['Mpesapin'])
                {
                    echo "Incorrect Mpesa Pin, Please try again!";
                    //confirmAgentWithdrawal();
                }
                else{

                    confirmAgentWithdrawal();

                }
            }
        }

    }
}

function confirmAgentWithdrawal()
{
    echo "<form action='safuser.php' method='POST'>
                    <h3>Withdraw cash from Agent ----- Kshs. -----</h3>
                    <button type='submit' name='cancelagent'>cancel</button>
                    <button type='submit' name='okagent'>OK</button>
                    
                 </form>";
}

function confirmAtmWithdrawal()
{
    echo "<form action='safuser.php' method='POST'>
                    <h3>Apply Voucher From ATM -----(agent number)</h3>
                    <button type='submit' name='cancelatm'>cancel</button>
                    <button type='submit' name='okatm'>OK</button>
                    
                 </form>";
}



if($_SERVER['REQUEST_METHOD'] == 'POST')
{
if(isset($_POST['vsendmoney'])) //To handle the send money button from virtual user i.e from Home.php
{
vsendMoney();
}

elseif(isset($_POST['vlipanampesa']))
{
    vLipaNaMpesa();
}

elseif(isset($_POST['vwithdrawmoney']))
{
vwithdrawMoney();
}

elseif(isset($_POST['Agent']))
{
vfromAgent();
}

elseif(isset($_POST['ATM']))
{
//echo "Ready to withdraw from ATM";
vfromAtm();
}

elseif(isset($_POST['withdrawfromagent']))
{
//echo "Ready to withdraw from ATM";
vfromAgentAmount();
}

elseif(isset($_POST['withdrawfromatm']))
{
//echo "Ready to withdraw from ATM";
vfromAtmAmount();
}

elseif(isset($_POST['withdrawfromagentamount']))
{
//echo "Ready to withdraw from ATM";
vagentMpesaPin();
}

elseif(isset($_POST['withdrawfromatmamount']))
{
//echo "Ready to withdraw from ATM";
vatmMpesaPin();
}

elseif(isset($_POST['mpin']))
{
//echo "Ready to withdraw from ATM";
vvalidateagentmpesapin();

}

elseif(isset($_POST['atmmpesapin']))
{
//echo "Ready to withdraw from ATM";
vconfirmAtmWithdrawal();
}

elseif(isset($_POST['cancelagent']))
{
header("Location: safusermenu.php");
}

elseif(isset($_POST['okagent']))
{
//header("Location: vsafusermenu");
echo "Thanks for staying with Safaricom. Your transaction will be processed sooner..";
}

elseif(isset($_POST['cancelatm']))
{
header("Location: safusermenu.php");
//echo "Thanks for staying with Safaricom. Your transaction will be processed sooner..";
}

elseif(isset($_POST['okatm']))
{
//header("Location: vsafusermenu");
echo "Thanks for staying with Safaricom. Your ATM withdrawal request will be processed sooner..";
}

}

//if($_SERVER['REQUEST_METHOD'] == 'POST')
//{

//}


?>