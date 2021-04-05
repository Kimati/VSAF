<?php
include("dbconnection.php");
if(isset($_POST['mpesapin']))
{
global $conn;
$mpesapin = $_POST['mpesapin'];
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
}
else{
header("Location:sendmoney.php");
}
}
}

}
}
?>