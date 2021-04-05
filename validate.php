<?php
/*
function check_login($conn)
{
    if (isset($_SESSION['phone'])) {
        $userphone = $_SESSION['phone'];
        $query = "select * from users where phone='$userphone' limit 1";
        $result = mysqli_query($conn, $query);

        //checking if there is a match of the credentials in the database
        if ($result && mysqli_num_rows($result) > 0) {
            $userdata = mysqli_fetch_assoc($result);
            return $userdata;
        }
  }
*/
include('dbconnection.php');

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $userphone=$_POST['phone'];
    $uservpin=$_POST['vpin'];
   // validating the login credentials
    if(!empty($userphone) && !empty($uservpin))
    {
        $query="select * from users where phone='$userphone' limit 1";
        $result=mysqli_query($conn,$query);

        if($result)
        {
            if(mysqli_num_rows($result) > 0)
            {
                $userdata=mysqli_fetch_assoc($result);
                    if($userdata['vpin'] === $uservpin)
                    {

                        header("Location: safusermenu.php");
                        die();
                    }
            }
        }
    }
    else
        {
        echo "Incorrect details,try again!";
           // header("Location: proceed.php");
    }
}