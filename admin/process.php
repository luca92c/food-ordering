<?php
// procedura di login (per ora niente encrypt delle password con sha1)
include ("../functions.php");
if (isset($_POST['username']) && isset($_POST['password']))
{

    // evito eventuali sql injection
    $username = $sqlconnection->real_escape_string($_POST['username']);
    $password = $sqlconnection->real_escape_string($_POST['password']);

    $sql = "SELECT * FROM tbl_admin WHERE username ='$username' AND password = '$password'";

    if ($result = $sqlconnection->query($sql)){
        if ($row = $result->fetch_array(MYSQLI_ASSOC)){
            $uid = $row['ID'];
            $username = $row['username'];

            $_SESSION['uid'] = $uid;
            $_SESSION['username'] = $username;
            $_SESSION['user_level'] = "admin";

            echo "correct";
        }
        else{
            echo "Credenziali errate";
        }
    }
}