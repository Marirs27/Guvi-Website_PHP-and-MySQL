<?php
    ob_start();
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors',1);
    if (!(array_key_exists("id",$_SESSION))) {
        header("Loaction: index.html");
    }
    $serverName = "sdb-c.hosting.stackcp.net";
    $userName = "myMoodleUsers-3137313d72";
    $password = "bizoimw133";
    $dbName = "myMoodleUsers-3137313d72";
    $link = mysqli_connect($serverName, $userName, $password, $dbName);
    $stmt = $link->prepare("SELECT * FROM users WHERE _id =? LIMIT 1");
    $stmt->bind_param('s', $id);
    $id = $_SESSION['id'];
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $row["password"]=$_SESSION['password'];
    print_r(json_encode($row)) ;    
    if (array_key_exists("updateSave", $_POST)) {
        $query = "SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['updatedEmail'])."'";
        $result = mysqli_query($link,$query);
        if (!(mysqli_num_rows($result)>1)) {
            /*$query = "UPDATE users SET firstName='".mysqli_real_escape_string($link,$_POST['updatedFirstName'])."',
                    lastName='".mysqli_real_escape_string($link,$_POST['updatedLastName'])."',
                    phnNumber='".mysqli_real_escape_string($link,$_POST['updatedNumber'])."',
                    email='".mysqli_real_escape_string($link,$_POST['updatedEmail'])."',
                    password='".mysqli_real_escape_string($link,$_POST['updatedPassword'])."' 
                    WHERE _id='".$_SESSION['id']."' LIMIT 1" ;
            $result = mysqli_query($link,$query); */
            $stmt = $link->prepare("UPDATE users SET firstName=?,lastName=?,phnNumber=?,email=?,password=? WHERE _id=?");
            $stmt->bind_param("sssssi", $firstName, $lastName,$phnNumber, $email, $password, $id);
            $firstName = mysqli_real_escape_string($link,$_POST['updatedFirstName']);
            $lastName = mysqli_real_escape_string($link,$_POST['updatedLastName']);
            $phnNumber = mysqli_real_escape_string($link,$_POST['updatedNumber']);
            $email = mysqli_real_escape_string($link,$_POST['updatedEmail']);
            $password = password_hash(mysqli_real_escape_string($link,$_POST['updatedPassword']), PASSWORD_DEFAULT);
            $id = $_SESSION['id'];
            $stmt->execute();
            $result = $stmt->get_result();
        }
    }
    ob_end_flush();
?>