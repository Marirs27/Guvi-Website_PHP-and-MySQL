<?php
    ob_start();
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    if (array_key_exists('logout',$_GET)) {
        unset($_SESSION);
    } 
    if (array_key_exists("signUpCheck", $_POST)) {
        $serverName = "sdb-c.hosting.stackcp.net";
        $userName = "myMoodleUsers-3137313d72";
        $password = "bizoimw133";
        $dbName = "myMoodleUsers-3137313d72";
        $link = mysqli_connect($serverName, $userName, $password, $dbName);
        if (mysqli_connect_error()) {
            die("DB connection error !");
        }
        else {
            $stmt = $link->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $email = mysqli_real_escape_string($link,$_POST['mail']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $_POST["errorCheck"] = "raiseError" ;
                $_POST["errorMsg"] = "Email address already exists !";
                print_r(json_encode($_POST));
            }
            else {
                $stmt = $link->prepare("INSERT INTO users (firstName,lastName,phnNumber,email,password) VALUES (?,?,?,?,?)");
                $stmt->bind_param("sssss", $firstName, $lastName,$phnNumber, $email, $password);
                $firstName = mysqli_real_escape_string($link,$_POST['fName']);
                $lastName = mysqli_real_escape_string($link,$_POST['lName']);
                $phnNumber = mysqli_real_escape_string($link,$_POST['pNumber']);
                $email = mysqli_real_escape_string($link,$_POST['mail']);
                $password = password_hash(mysqli_real_escape_string($link,$_POST['pass']), PASSWORD_DEFAULT);
                $stmt->execute();
                $result = $stmt->get_result();
            }
        }
    }
    if (array_key_exists("signInCheck", $_POST)) {
        $serverName = "sdb-c.hosting.stackcp.net";
        $userName = "myMoodleUsers-3137313d72";
        $password = "bizoimw133";
        $dbName = "myMoodleUsers-3137313d72";
        $link = mysqli_connect($serverName, $userName, $password, $dbName);
        if (mysqli_connect_error()) {
            die("DB connection error !");
        }
        else {
            $stmt = $link->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param('s', $email);
            $email = mysqli_real_escape_string($link,$_POST['email']);
            $stmt->execute();
            $result = $stmt->get_result();
            if (!($row = $result->fetch_assoc())) {
                $_POST["errorCheck"] = "raiseError";
                $_POST["errorMsg"] = "Wrong Credentials, Try Again !";
                print_r(json_encode($_POST)) ;
            }
            else {
                if (password_verify($_POST['pass'],$row['password'])) {
                    $_SESSION['id']=$row['_id'];
                    $_SESSION['password']=$_POST['pass'];
                    $_SESSION['email']=$row['email'];
                    $_POST["signInCheck"] = "allowSignIn" ;
                }
                else {
                    $_POST["signInCheck"] = "denySignIn" ;
                    $_POST["errorCheck"] = "raiseError";
                    $_POST["errorMsg"] = "Wrong Credentials, Try Again !";
                }
                print_r(json_encode($_POST)) ;
            }
        }
    }
    ob_end_flush();
?>