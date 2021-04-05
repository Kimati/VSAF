<?php
session_start();
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
        echo "<form action='vsaflipanampesa.php' method='POST'>
         <label>Enter till no.</label><br>
         <input type='number' name='tillno'><br>
         <button type='submit' name='oktillno'>OK</button>
    </form>";
    }
    elseif(isset($_POST['businessnumber']))
    {
        echo "<form action='vsaflipanampesa.php' method='POST'>
         <label>Enter business no.</label><br>
         <input type='number' name='businessno'><br>
         <button type='submit' name='okbusinessno'>OK</button>
    </form>";
    }
    elseif(isset($_POST['businessno']))
    {
        $_SESSION['vbusinessnumber'] = $_POST['businessno'];
        echo "<form action='vsaflipanampesa.php' method='POST'>
          <button type='submit' name='accsimcontacts'>Search SIM Contacts</button>
          <button type='submit' name='accountnumber'>Account no.</button>
    </form>";
    }

    elseif(isset($_POST['accountnumber']))
    {
        echo "<form action='vsaflipanampesa.php' method='POST'>
         <label>Account no.</label><br>
         <input type='string' name='accountno'><br>
         <button type='submit' name='okaccountno'>OK</button>
    </form>";
    }

    elseif(isset($_POST['accountno']))
    {
        $_SESSION['vaccountnumber'] = $_POST['accountno'];

        echo "<form action='vsaflipanampesa.php' method='POST'>
         <label>Enter Amount.</label><br>
         <input type='number' name='lipaamount'><br>
         <button type='submit' name='oklipa'>OK</button>
    </form>";
    }

    elseif(isset($_POST['lipaamount']))
    {
        $_SESSION['vlipaamount'] = $_POST['lipaamount'];

        echo "<form action='vsaflipanampesa.php' method='POST'>
         <label>Enter M-PESA PIN</label><br>
         <input type='number' name='mpesapin'><br>
         <button type='submit' name='okmpin'>OK</button>
    </form>";
    }

    elseif(isset($_POST['mpesapin']))
    {
        $_SESSION['vmpesapin'] = $_POST['mpesapin'];

        $payBill = $_SESSION['vbusinessnumber'];
        $accNumber = $_SESSION['vaccountnumber'];
        $Amount =  $_SESSION['vlipaamount'];

        echo "<form action='validatevlipanampesa.php' method='POST'>
          <p>Pay Bill $payBill Account no. $accNumber KSH.$Amount</p>
          <button type='submit' name='abort'>CANCEL</button>
          <button type='submit' name='confirm'>OK</button>
    </form>";
    }

}