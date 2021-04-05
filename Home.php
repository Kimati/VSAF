
<?php
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['0797801442']))
    {
       // $_POST['0797801442'] = "0797801442";
        //$normaluser = $_POST['0797801442'];
        $_SESSION['activenormaluser'] = "0797801442";
       // $line1normaluserno = $_SESSION['0797801442'];
        //echo $fnormaluser;
    }
    elseif(isset($_POST['0708000178']))
    {
        //$_POST['0708000178'] = "0708000178";
        //$normaluser = $_POST['0708000178'];
        $_SESSION['activenormaluser'] = "0708000178";
        //$line2normaluserno = $_SESSION['0708000178'];
        //echo $snormaluser;

    }
}
?>
<form action="safuser.php" method="POST">
       <button type="submit" name="sendmoney">Send Money</button><br><br>
        <button type="submit" name="withdrawmoney">Withdraw Cash</button><br><br>
        <button type="submit" name="buyairtime">Buy Airtime</button><br><br>
        <button type="submit" name="loans&savings">Loans and Savings</button><br><br>
        <button type="submit" name="lipanampesa">Lipa na Mpesa</button><br><br>
        <button type="submit" name="myaccount">My account</button><br><br>

</form>
<form action="proceed.php" method="POST">
    <button type="submit" name="myvaccount">VSAF</button><br><br>
</form>