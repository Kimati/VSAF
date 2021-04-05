

<?php
session_start();

include('dbconnection.php');

if($_SERVER['REQUEST_METHOD'] == "POST") {
    global $conn;
    if (isset($_POST['phone']) && isset($_POST['vpin'])) {
        $vuserphone = $_POST['phone'];
        $vuserpin = $_POST['vpin'];
// validating the login credentials
        if (!empty($vuserphone) && !empty($vuserpin)) {
            $vactiveuserphone = "select * from users where phone = '$vuserphone' limit 1";
            $result = mysqli_query($conn, $vactiveuserphone);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $vuserdata = mysqli_fetch_assoc($result);
                   if ($vuserdata['vpin'] === $vuserpin) {

                        //$vuserno = $userdata['phone'];

                        // header("Location: safusermenu.php");
                        //die();
                        //Creating a session for the person accessing the vsaf version.
                        $_SESSION['virtualSenderNumber'] = $vuserdata['phone'];
                        $_SESSION['virtualVpin'] = $vuserdata['vpin'];

                        header("Location: safusermenu.php");
                    } elseif ($vuserdata['vpin'] !== $vuserpin) {
                        //header("Location: proceed.php");
                        echo "Incorrect VSAF Pin, Please try again.";
                    } elseif ($vuserdata['phone'] !== $vuserphone) {
                        echo "That phone number is not registered to use the Virtual safaricom service.";
                    }
                }
            }
        } else {
            //header("Location: proceed.php");
            echo "Please fill out all fields!";

        }
    }
}
?>

<form action="proceed.php" method="POST">
    Phone Number: <input type="number" name="phone"><br><br>
    VPin: <input type="number" name="vpin"> <br><br>
    <button type="submit">submit</button>
</form>
