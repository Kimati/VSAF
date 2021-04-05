<?php
//This file checks if the user has initiated a lipa na mpesa request by ckecking if the user has clicked "Paybill" or "Buy goods and services"
session_start();

function bsNoOption()
{
    echo "<form action='saflipanampesa.php' method='POST'>
          <button type='submit' name='simcontacts'>Search SIM Contacts</button>
          <button type='submit' name='businessnumber'>Enter business no</button>
    </form>";
}

function tillNumber()
{
    echo "<form action='saflipanampesa.php' method='POST'>
         <label>Enter till no.</label><br>
         <input type='number' name='tillno'><br>
         <button type='submit' name='oktillno'>OK</button>
    </form>";
}

function businessNumber()
{
    echo "<form action='saflipanampesa.php' method='POST'>
         <label>Enter business no.</label><br>
         <input type='number' name='businessno'><br>
         <button type='submit' name='okbusinessno'>OK</button>
    </form>";
}

function accNumberPrompt()
{
    echo "<form action='saflipanampesa.php' method='POST'>
          <button type='submit' name='accsimcontacts'>Search SIM Contacts</button>
          <button type='submit' name='accountnumber'>Account no.</button>
    </form>";
}

function accountNumber()
{
    echo "<form action='saflipanampesa.php' method='POST'>
         <label>Account no.</label><br>
         <input type='string' name='accountno'><br>
         <button type='submit' name='okaccountno'>OK</button>
    </form>";
}

 function lipaAmount()
 {
     echo "<form action='saflipanampesa.php' method='POST'>
         <label>Enter Amount.</label><br>
         <input type='number' name='lipaamount'><br>
         <button type='submit' name='oklipa'>OK</button>
    </form>";
 }

 function  confirmPayment()
 {
     $payBill = $_SESSION['businessnumber'];
     $accNumber = $_SESSION['accountnumber'];
     $Amount =  $_SESSION['lipaamount'];

     echo "<form action='validatelipanampesa.php' method='POST'>
          <p>Pay Bill $payBill Account no. $accNumber KSH.$Amount</p>
          <button type='submit' name='abort'>CANCEL</button>
          <button type='submit' name='confirm'>OK</button>
    </form>";
 }

function mpesaPin()
{
    echo "<form action='saflipanampesa.php' method='POST'>
         <label>Enter M-PESA PIN</label><br>
         <input type='number' name='mpesapin'><br>
         <button type='submit' name='okmpin'>OK</button>
    </form>";
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['paybill']))
    {
        bsNoOption();
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